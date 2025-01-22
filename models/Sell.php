<?php

namespace models;

class Sell
{

    public $id;

    public $sale_date;

    public $client_id;

    public $seller_id;

    public $total;

    public $address_id;

    public $postal_code;

    public $street_number;

    public $street;

    public $district;

    public $state;

    public $city;

    public $complement;

    public $items = [];
}
