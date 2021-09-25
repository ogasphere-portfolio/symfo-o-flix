# Nos routes

| url                       | controller     | fonction    | methode   | remarque                                        |
| ------------------------- | -------------- | ----------- | --------- | ----------------------------------------------- |
| /admin/category/browse    | Admin/Category | browse      | GET       | Affiche la liste                                |
| /admin/category/read/{id} | Admin/Category | read        | GET       | Affiche une catégorie                           |
| /admin/category/edit/{id} | Admin/Category | edit        | GET, POST | Affiche le formulaire d'édition d'une catégorie |
| /admin/category/add       | Admin/Category | add         | GET, POST | Affiche le formulaire de création               |
| /admin/category/delete    | Admin/Category | delete      | GET       | Supprime la catégorie demandée                  |