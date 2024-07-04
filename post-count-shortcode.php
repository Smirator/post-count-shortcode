<?php
/*
Plugin Name: Post Count Shortcode
Description: Добавляет шорткод для отображения последних постов на сайте. Тестовое задание
Version: 1.1
Author: Шкарбан Руслан
*/

//проверяем, что классы определены один раз
if (!class_exists('Post_Count_Shortcode')) {
    class Post_Count_Shortcode {

        private $log;

        public function __construct() {
            //подключаем композер
            if (file_exists(__DIR__ . '/vendor/autoload.php')) {
                require_once __DIR__ . '/vendor/autoload.php';
            } else {
                add_action('admin_notices', [$this, 'autoload_missing_notice']);
                return;
            }

            //логгер
            $this->log = new \Monolog\Logger('post_count');
            $this->log->pushHandler(new \Monolog\Handler\StreamHandler(__DIR__ . '/post_count.log', \Monolog\Logger::ERROR));

            // регистрируем шорткод
            add_shortcode('post_count', [$this, 'post_count_shortcode']);

            // определяем настройки
            add_action('admin_menu', [$this, 'post_count_plugin_menu']);
            add_action('admin_init', [$this, 'post_count_register_settings']);
        }

        public function autoload_missing_notice() {
            echo '<div class="error"><p>Файл автозагрузки Composer отсутствует. Пожалуйста, выполните "composer install" в директории плагина.</p></div>';
        }

        public function post_count_shortcode($atts) {
            // Получаем количество постов из настроек или используем поумолчанию
            $options = get_option('post_count_display_options');
            $default_count = isset($options['post_count_number']) ? $options['post_count_number'] : 10;

        
            $atts = shortcode_atts(array(
                'type' => 'post',
                'count' => $default_count,    
            ), $atts, 'post_count');


            $args = array(
                'post_type' => $atts['type'],
                'posts_per_page' => $atts['count'],
                'post_status' => 'publish',
                'orderby' => 'date',
                'order' => 'DESC'
            );

            
            $query = new WP_Query($args);
            if ($query->have_posts()) {
                $output = '<div class="post-count-shortcode">';
                while ($query->have_posts()) {
                    $query->the_post();
                    $output .= '<div class="post-item">';
                    if (has_post_thumbnail()) {
                        $output .= '<div class="post-thumbnail">' . get_the_post_thumbnail() . '</div>';
                    }
                    $output .= '<h2 class="post-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
                    $output .= '</div>';
                }
                wp_reset_postdata();
                $output .= '</div>';
            } else {
                $output = 'Нет постов для отображения.';
            }

            return $output;
        }

        public function post_count_plugin_menu() {
            add_submenu_page(
                'options-general.php',
                'Настройки Post Count',
                'Настройки Post Count',
                'manage_options',
                'post_count_settings',
                [$this, 'post_count_settings_page']
            );
        }

        public function post_count_settings_page() {
            ?>
            <div class="wrap">
                <h2>Настройки Post Count</h2>
                <form method="post" action="options.php">
                    <?php settings_fields('post_count_options'); ?>
                    <?php do_settings_sections('post_count_options'); ?>
                    <?php submit_button(); ?>
                </form>
            </div>
            <?php
        }

        public function post_count_register_settings() {
            register_setting('post_count_options', 'post_count_display_options', [$this, 'post_count_sanitize_options']);

            add_settings_section(
                'post_count_display_section',
                'Опции отображения',
                [$this, 'post_count_display_section_callback'],
                'post_count_options'
            );

            add_settings_field(
                'post_count_number',
                'Количество постов для отображения',
                [$this, 'post_count_number_callback'],
                'post_count_options',
                'post_count_display_section'
            );
        }

        public function post_count_sanitize_options($input) {
            $sanitized_input = array();
            if (isset($input['post_count_number'])) {
                $sanitized_input['post_count_number'] = intval($input['post_count_number']);
            }
            return $sanitized_input;
        }

        public function post_count_display_section_callback() {
            echo '<p>Укажите параметры отображения количества постов.</p>';
        }

        public function post_count_number_callback() {
            $options = get_option('post_count_display_options');
            $count = isset($options['post_count_number']) ? $options['post_count_number'] : 10;
            echo '<input type="number" id="post_count_number" name="post_count_display_options[post_count_number]" value="' . esc_attr($count) . '" />';
        }
    }

    //инициализируем плагин
    new Post_Count_Shortcode();
}
?>
