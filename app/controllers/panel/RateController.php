<?php
namespace App\Controllers\Panel;

use App\Core\{Controller, CSRF};
use App\Models\RateModel;

class RateController extends Controller
{
    private RateModel $model;

    public function middleware(): void { $this->requireRole('sub_admin'); }

    public function __construct() { $this->model = new RateModel(); }

    public function index(): void
    {
        $this->view('panel.rates.index', [
            'pageTitle' => 'Live Rates',
            'rates'     => $this->model->allRates(),
            'types'     => ['gold'=>'Gold (per gram)','silver'=>'Silver (per gram)',
                            'petrol'=>'Petrol (per litre)','diesel'=>'Diesel (per litre)',
                            'currency_usd'=>'USD/INR','currency_gbp'=>'GBP/INR','currency_eur'=>'EUR/INR'],
        ], 'subadmin');
    }

    public function store(): void
    {
        CSRF::validate();
        $type   = $this->post('type','gold');
        $value  = (float)$this->post('value',0);
        $city   = trim($this->post('city','')) ?: null;
        $change = $this->post('change','') !== '' ? (float)$this->post('change',0) : null;

        if ($value <= 0) {
            $this->flash('danger','Enter a valid rate value.');
            $this->redirect('/panel/rates');
        }
        $this->model->upsert($type, $value, $city, $change);
        $this->flash('success','Rate updated successfully.');
        $this->redirect('/panel/rates');
    }
}
