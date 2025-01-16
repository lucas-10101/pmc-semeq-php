
/*Create and configure the user*/
CREATE TABLESPACE PMC DATAFILE 'pmc.df' SIZE 10M ONLINE;
CREATE USER "PMC" IDENTIFIED BY "PMC-APPLICATION-USER";
ALTER USER PMC DEFAULT TABLESPACE PMC QUOTA 10M ON PMC;
commit;


CREATE TABLE "Users" (
    "id" INT GENERATED ALWAYS AS IDENTITY(START WITH 1 INCREMENT BY 1) PRIMARY KEY,
    "email" VARCHAR(255) NOT NULL UNIQUE,
    "password" VARCHAR(72) NOT NULL,
    "role" VARCHAR(32) NOT NULL
);

CREATE TABLE "Sellers" (
    "id" INT GENERATED ALWAYS AS IDENTITY(START WITH 1 INCREMENT BY 1) PRIMARY KEY,
    "name" VARCHAR(80) NOT NULL,
    "user_id" INT NOT NULL REFERENCES "Users"("id")
);

CREATE TABLE "Clients" (
    "id" INT GENERATED ALWAYS AS IDENTITY(START WITH 1 INCREMENT BY 1) PRIMARY KEY,
    "name" VARCHAR(255) NOT NULL,
    "user_id" INT NOT NULL REFERENCES "Users"("id")
);
commit;

CREATE TABLE "Products" (
    "id" INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    "name" VARCHAR(100) NOT NULL,
    "price" DECIMAL(12, 2) NOT NULL 
);
commit;

/* First user */
INSERT INTO "Users" ("email", "role", "password") VALUES ('admin@locahost.com', 'SELLER', '1234');
INSERT INTO "Sellers" ("name", "user_id") VALUES ('admin', 1);
commit;

INSERT INTO "Products" ("name", "price") VALUES ('Product 1', 10000.00);
INSERT INTO "Products" ("name", "price") VALUES ('Product 2', 120.00);
INSERT INTO "Products" ("name", "price") VALUES ('Product 3', 140.00);
INSERT INTO "Products" ("name", "price") VALUES ('Product 4', 160.00);
INSERT INTO "Products" ("name", "price") VALUES ('Product 5', 180.00);
INSERT INTO "Products" ("name", "price") VALUES ('Product 6', 200.00);
INSERT INTO "Products" ("name", "price") VALUES ('Product 7', 220.00);
commit;

CREATE TABLE "Suppliers" (
    "id" INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    "name" VARCHAR(64) NOT NULL
);
commit;

CREATE TABLE "Product_Suppliers" (
    "product_id" INT NOT NULL REFERENCES "Products"("id"),
    "supplier_id" INT NOT NULL REFERENCES "Suppliers"("id"),
    CONSTRAINT "Product_Suppliers_PK" PRIMARY KEY("product_id", "supplier_id")
);
commit;


SELECT
    PS."product_id",
    P."name" AS "product_name",
    PS."supplier_id",
    S."name" AS "supplier_name"
FROM 
    "Product_Suppliers" PS
    INNER JOIN "Products" P ON P."id" = PS."product_id"
    INNER JOIN "Suppliers" S ON S."id" = PS."supplier_id"
    

SELECT
    COUNT(*) AS "count"
FROM 
    "Product_Suppliers" PS
    INNER JOIN "Products" P ON P."id" = PS."product_id"
    INNER JOIN "Suppliers" S ON S."id" = PS."supplier_id"
    
INSERT INTO "Product_Suppliers" ("product_id", "supplier_id") VALUES()
