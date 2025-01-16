#Projeto PMC SEMEQ

- Este projeto utiliza banco de dados Oracle (Testado na versão 21c XE) e as configurações do usuário podem ser encontradas no script SQL dentro da pasta /uml
- As configurações de conexão podem ser alteradas de acordo com as suas configurações locais e estão disponveis em /classes/DatabaseConnection.php

```
/*Create and configure the user*/
CREATE TABLESPACE PMC DATAFILE 'pmc.df' SIZE 10M ONLINE;
CREATE USER "PMC" IDENTIFIED BY "PMC-APPLICATION-USER";
ALTER USER PMC DEFAULT TABLESPACE PMC QUOTA 10M ON PMC;
commit;
```

- Para utilizar o sistema, é necessario injetar um usuário inicial no banco de dados (Auto cadastro indisponível no momento)
```
/* First user */
INSERT INTO "Users" ("email", "role", "password") VALUES ('admin@locahost.com', 'SELLER', '1234');
INSERT INTO "Sellers" ("name", "user_id") VALUES ('admin', 1);
commit;
```

- Esta aplicação não utiliza algoritmos de criptografia como BCrypt ou MD5 para criptografia (issue será aberta futuramente?)