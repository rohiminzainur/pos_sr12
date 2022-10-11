<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Expense;
use PDF;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $dateStart = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $dateEnd = date('Y-m-d');

        if ($request->has('date_start') && $request->date_start != "" && $request->has('date_end') && $request->date_end) {
            $dateStart = $request->date_start;
            $dateEnd = $request->date_end;
        }


        return view('report.index', compact('dateStart', 'dateEnd'));
    }

    public function getData($start, $end)
    {
        $no = 1;
        $data = array();
        $income = 0;
        $total_income = 0;

        while (strtotime($start) <= strtotime($end)) {
            $date = $start;
            $start = date('Y-m-d', strtotime("+1 day", strtotime($start)));

            $total_sale = Sale::where('created_at', 'LIKE', "%$date%")->sum('paid');
            $total_purchase = Purchase::where('created_at', 'LIKE', "%$date%")->sum('paid');
            $total_expense = Expense::where('created_at', 'LIKE', "%$date%")->sum('nominal');

            $income = $total_sale - $total_purchase - $total_expense;
            $total_income += $income;

            $row = array();
            $row['DT_RowIndex'] = $no++;
            $row['date'] = tanggal_indonesia($date, false);
            $row['sale'] = format_uang($total_sale);
            $row['purchase'] = format_uang($total_purchase);
            $row['expense'] = format_uang($total_expense);
            $row['income'] = format_uang($income);

            $data[] = $row;
        }
        $data[] = [
            'DT_RowIndex' => '',
            'date' => '',
            'sale' => '',
            'purchase' => '',
            'expense' => 'Total Income',
            'income' => format_uang($total_income),
        ];
        return $data;
    }

    public function data($start, $end)
    {
        $data = $this->getData($start, $end);

        return datatables()
            ->of($data)
            ->make(true);
    }

    public function exportPDF($start, $end)
    {
        $data = $this->getData($start, $end);
        $pdf = PDF::loadView('report.pdf', compact('start', 'end', 'data'));
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream('Report-Income-' . date('Y-m-d-his') . '.pdf');
    }
}