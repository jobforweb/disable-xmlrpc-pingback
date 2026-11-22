=== Disable XML-RPC & Pingback Completely ===
Contributors: lvi
Tags: xml-rpc, pingback, security, disable, woocommerce
Requires at least: 5.3
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Полностью отключает XML-RPC, pingback/trackback, заголовок X-Pingback и тег link rel="pingback".

== Description ==

Мини-плагин закрывает все основные каналы XML-RPC и pingback на сайте WordPress:

* отклоняет запросы к xmlrpc.php и обнуляет список доступных методов;
* запрещает pingback и trackback для всех записей;
* удаляет HTTP-заголовок X-Pingback;
* убирает ссылки RSD и Windows Live Writer из wp_head;
* вырезает тег `<link rel="pingback">` из готового HTML, включая страницы WooCommerce.

Плагин не зависит от темы оформления, не требует WooCommerce, не сохраняет опции и не создаёт таблиц в базе данных. Все функции имеют префикс dxp_.

== Installation ==

1. Загрузите папку disable-xmlrpc-pingback в /wp-content/plugins/ или установите плагин через раздел «Плагины → Добавить новый».
2. Активируйте «Disable XML-RPC & Pingback Completely» в разделе «Плагины → Установленные».
3. Очистите кэш страниц, CDN и браузера.
4. Проверьте исходный код страницы: тег rel="pingback" должен отсутствовать.

Альтернатива — добавить эквивалентный код в functions.php дочерней темы, но не одновременно с плагином. Подробности в README.md.

== Frequently Asked Questions ==

= Плагин сломает Jetpack? =

Да, сервисы, использующие XML-RPC для связи с wordpress.com (в первую очередь Jetpack), перестанут работать. Если они нужны — не активируйте плагин.

= Нужно ли что-то менять в .htaccess? =

Нет. Отключение выполняется хуками WordPress, файлы ядра и конфигурация сервера не трогаются.

= Тег pingback всё ещё виден в исходнике =

Сбросьте кэш и проверьте, нет ли дублирующего кода в functions.php темы или в другом плагине. Если разметка формируется после template_redirect, проверьте итоговый HTML.

= Что удаляется при деактивации? =

Ничего. Плагин не хранит данных, поэтому uninstall.php не требуется.

== Changelog ==

= 1.1.0 =
* Добавлены текстовый домен, лицензия и требования к версиям в заголовке плагина.
* Функции переименованы с префиксом dxp_, добавлен фильтр xmlrpc_methods.
* Отключение wlwmanifest_link, регистронезависимое удаление X-Pingback.
* Буфер вывода ограничен фронтенд-HTML; добавлены readme.txt и README.md.

= 1.0 =
* Первоначальная версия.
