# Проект Дерево данных
## База данных
### users
id     - primary index<br>
name   - имя<br>
email  - емайл, уникальный, логин<br>
role   - права пользователя (user/admin) <br>
### datatree
id          - primary index<br>
parent_id   - id родительского элемента<br>
title       - название<br>
description - описание<br>

При регистрации - права пользователя - user<br>
Право на изменение данных имеет пользователь с правами admin<br>
административный логин/пароль:<br>
admin@<br>
111