URI Route
============

The module allows you to replace the URI to another address using regular expressions and other rules.


What this module can do:

   * create strong uri path to simple (example  /doctors/doctor/vasya-pupkin/video to /doctors/doctor_video/vasya-pupkin)
   * testing site functional for custom member groups (for example testing new design)
   * clearly path (for pagination, category, etc..)
   * marge segments (example /entry_id+url_title/ to /entry_id/url_title/)
   * validate path (for example check integer value in segment)
   * redirect from old path to new path
   * promo action for limit time (example path /promo)

RU
=====

Модуль позволяет подменить URI на другой адрес используя регулярные выражения и другие правила.

Варианты использования:

  * сложные пути URL заменять на простые (пример: /doctors/doctor/vasya-pupkin/video на /doctors/doctor_video/vasya-pupkin)
  * тестирование функционала ограниченным кругом лиц (например тестирование нового дизайна только админами)
  * красивые пути (например пагинации, категорий, и.т.д.)
  * сложное разложение путей на сегменты (например /entry_id+url_title/ на /entry_id/url_title/)
  * валидация путей, например если ожидаете числа в сегменте
  * эксклюзивные пути например http://site.name/vip/ покажет страницу http://site.name/article/premium-account
  * редиректы со старых путей на новые для SEO оптимизации

О нагрузке
Модуль выполняет всего 1 запрос на 1 страницу сайта, после чего проверяет все включенные правила на совпадение по регулярным выражениям. При совпадении модуль меняет значение в ядре движка и завешается.
