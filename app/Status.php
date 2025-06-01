<?php

namespace App;

enum Status :string
{
    case Pending = "Pending";
    case Confirmed = "Confirmed";
    case Cancelled = "Cancelled";
}
