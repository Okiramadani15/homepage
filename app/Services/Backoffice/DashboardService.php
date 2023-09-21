<?php

namespace App\Services\Backoffice;

use App\Models\Backoffice\Assets\Asset;
use App\Models\Backoffice\Assets\Loan;
use App\Models\Backoffice\Assets\Procurement;
use App\Models\Backoffice\Assets\Repair;

class DashboardService {
    public function index(){
        try{
            $good = Asset::sum('condition_good');
            $not_good = Asset::sum('condition_not_good');
            $very_bad = Asset::sum('condition_very_bad');
            $total_asset = $good + $not_good + $very_bad;

            $data = [
                'good' => $good,
                'not_good' => $not_good,
                'very_bad' => $very_bad,
                'total_asset' => $total_asset,
            ];

            return response()->json([
                'status' => 'success',
                'data' => $data,
                'message' => 'Success'
            ]);
        }catch(Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function chart(){
        try{
            $month = date('m');
            $year = date('Y');

            $labels = [];
            $years = [];
            $DataLoan = [];
            $DataProcurement = [];
            $DataRepair = [];

            $loan = Loan::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->count();

            $procurement = Procurement::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->count();

            $repair = Repair::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->count();
            
            array_push($labels, $this->monthIndo($month) . '/' . $year);
            array_push($years, $year);
            array_push($DataLoan, $loan);
            array_push($DataProcurement, $procurement);
            array_push($DataRepair, $repair);

            for($i = 1; $i <= 11; $i++){
                $date = date('Y-m-d', strtotime(date('Y-m')." -" .$i . " month"));
                $dt = date_create($date);
                $month = $dt->format('m');
                
                $loan = Loan::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->count();

                $procurement = Procurement::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->count();

                if($year != $dt->format('Y')){
                    $year = $dt->format('Y');
                    array_push($years, $year);
                }

                $repair = Repair::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->count();

                array_push($labels, $this->monthIndo($dt->format('m')) . '/' . $year);
                array_push($DataLoan, $loan);
                array_push($DataProcurement, $procurement);
                array_push($DataRepair, $repair);
            }
            sort($years);

            $data = [
                'labels' => $labels,
                'years' => $years,
                'data_loan' => $DataProcurement,
                'data_procurement' => $DataProcurement,
                'data_repair' => $DataRepair,
            ];

            return response()->json([
                'status' => 'success',
                'data' => $data,
                'message' => 'Success'
            ]);

        }catch(Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function monthIndo($month){
        if($month == '01'){
            $monthName = "Januari";
        }elseif($month == '02'){
            $monthName = "Februari";
        }elseif($month == '03'){
            $monthName = "Maret";
        }elseif($month == '04'){
            $monthName = "April";
        }elseif($month == '05'){
            $monthName = "Mei";
        }elseif($month == '06'){
            $monthName = "Juni";
        }elseif($month == '07'){
            $monthName = "Juli";
        }elseif($month == '08'){
            $monthName = "Agustus";
        }elseif($month == '09'){
            $monthName = "September";
        }elseif($month == '10'){
            $monthName = "Oktober";
        }elseif($month == '11'){
            $monthName = "November";
        }elseif($month == '12'){
            $monthName = "Desember";
        }else{
            $monthName = "Unknown";
        }
        
        return $monthName;
    }
}