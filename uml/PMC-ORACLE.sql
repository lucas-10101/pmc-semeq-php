
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

CREATE TABLE "Address" (
    "id" INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    "postal_code" VARCHAR(9) NOT NULL,
    "street_number" INT NOT NULL,
    "street" VARCHAR(200) NOT NULL,
    "district" VARCHAR(64) NOT NULL,
    "state" VARCHAR(2) NOT NULL,
    "city" VARCHAR(96) NOT NULL,
    "complement" VARCHAR(200) NOT NULL
);

CREATE TABLE "Sales" (
    "id" INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    "sale_date" DATE NOT NULL,
    "client_id" INT REFERENCES "Clients"("id") NOT NULL,
    "seller_id" INT REFERENCES "Sellers"("id") NOT NULL,
    "total" DECIMAL(12,2) NOT NULL,
    "address_id" INT REFERENCES "Address"("id") NOT NULL
);

CREATE TABLE "Sale_Products" (
    "sale_id" INT NOT NULL REFERENCES "Sales"("id"),
    "product_id" INT NOT NULL REFERENCES "Products"("id"),
    "quantity" INT NOT NULL,
    CONSTRAINT "Sell_Products_PK" PRIMARY KEY ("sale_id", "product_id")
);

commit;