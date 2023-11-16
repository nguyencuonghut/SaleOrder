<?php

namespace App\Livewire;

use App\Models\OrdersProducts;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DashboardComponent extends Component
{
    public $schedule_id;
    public $level1_manager_id;

    public function mount()
    {
        $schedule = Schedule::orderBy('id', 'desc')->first();
        $this->schedule_id = $schedule->id;
        $this->level1_manager_id = 'All';
    }

    public function exportExcel()
    {
        //Get the products data
        $schedule_id = $this->schedule_id;
        $level1_manager_id = $this->level1_manager_id;
        switch(Auth::user()->role->name){
            case 'Admin':
            case 'Sản Xuất':
                //Get all products of all orders
                if('All' == $level1_manager_id){
                    $products = OrdersProducts::where('is_deleted', false)
                    ->join('products','orders_products.product_id', '=', 'products.id')
                    ->join('orders as order', function ($join) use ($schedule_id) {
                        $join->on('orders_products.order_id', '=', 'order.id')
                            ->where('order.status','=','Giám đốc đã duyệt')
                            ->where('order.schedule_id', '=', $schedule_id);
                    })
                    ->select('products.*', 'orders_products.*', DB::raw('sum(orders_products.quantity) AS quantity'))
                    ->groupBy('products.id')
                    ->get();
                }else{
                    //Filter by level1_manager_id
                    $products = OrdersProducts::where('is_deleted', false)
                    ->join('products','orders_products.product_id', '=', 'products.id')
                    ->join('orders as order', function ($join) use ($schedule_id, $level1_manager_id) {
                        $join->on('orders_products.order_id', '=', 'order.id')
                            ->where('order.status','=','Giám đốc đã duyệt')
                            ->where('order.schedule_id', '=', $schedule_id)
                            ->where('order.level1_manager_id', '=', $level1_manager_id);
                    })
                    ->select('products.*', 'orders_products.*', DB::raw('sum(orders_products.quantity) AS quantity'))
                    ->groupBy('products.id')
                    ->get();
                }
                break;
            case 'Giám đốc':
                //Get only the products of the orders that approved as level1_manager_id
                $products = OrdersProducts::where('is_deleted', false)
                ->join('products','orders_products.product_id', '=', 'products.id')
                ->join('orders as order', function ($join) use ($schedule_id) {
                    $join->on('orders_products.order_id', '=', 'order.id')
                        ->where('order.status','=','Giám đốc đã duyệt')
                        ->where('order.schedule_id', '=', $schedule_id)
                        ->where('order.level1_manager_id', '=', Auth::user()->id);
                })
                ->select('products.*', 'orders_products.*', DB::raw('sum(orders_products.quantity) AS quantity'))
                ->groupBy('products.id')
                ->get();
                break;
            case 'TV/GS':
                //Get only the products of the orders that approved as level2_manager_id
                $products = OrdersProducts::where('is_deleted', false)
                ->join('products','orders_products.product_id', '=', 'products.id')
                ->join('orders as order', function ($join) use ($schedule_id) {
                    $join->on('orders_products.order_id', '=', 'order.id')
                        ->where('order.status','=','Giám đốc đã duyệt')
                        ->where('order.schedule_id', '=', $schedule_id)
                        ->where('order.level2_manager_id', '=', Auth::user()->id);
                })
                ->select('products.*', 'orders_products.*', DB::raw('sum(orders_products.quantity) AS quantity'))
                ->groupBy('products.id')
                ->get();
                break;
            case 'Nhân viên':
                //Get only the products of the orders that creator as creator_id
                $products = OrdersProducts::where('is_deleted', false)
                ->join('products','orders_products.product_id', '=', 'products.id')
                ->join('orders as order', function ($join) use ($schedule_id) {
                    $join->on('orders_products.order_id', '=', 'order.id')
                        ->where('order.status','=','Giám đốc đã duyệt')
                        ->where('order.schedule_id', '=', $schedule_id)
                        ->where('order.creator_id', '=', Auth::user()->id);
                })
                ->select('products.*', 'orders_products.*', DB::raw('sum(orders_products.quantity) AS quantity'))
                ->groupBy('products.id')
                ->get();
                break;
        }

        $schedule = Schedule::findOrFail($schedule_id);
        //Export to Excel
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->setTitle("Tổng hợp");

        //Set font
        $styleArray = array(
            'font'  => array(
                 'name'  => 'Times New Roman'
             ),
        );
        $spreadsheet->getDefaultStyle()
                    ->applyFromArray($styleArray);

        //Set column width
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(3);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(30);

        //Fill the title
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setCellValue('B2', 'KẾ HOẠCH ĐẶT HÀNG SẢN XUẤT');
        $spreadsheet->getActiveSheet()
                    ->getStyle("B2")
                    ->getFont()
                    ->setSize(13)
                    ->setBold(true);

        $spreadsheet->getActiveSheet()->mergeCells("B2:D2");
        $spreadsheet->getActiveSheet()->mergeCells("B3:D3");
        $spreadsheet->getActiveSheet()
                    ->getStyle('B2')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()
                    ->getStyle('B3')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $activeWorksheet->setCellValue('B3', $schedule->title);
        $spreadsheet->getActiveSheet()
                    ->getStyle("B3")
                    ->getFont()
                    ->setSize(13)
                    ->setBold(true);

        //Fill the column names
        $activeWorksheet->setCellValue('B5', 'STT');
        $spreadsheet->getActiveSheet()
                    ->getStyle("B5")
                    ->getFont()
                    ->setBold(true);

        $spreadsheet->getActiveSheet()
                    ->getStyle("B5")
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->setCellValue('C5', 'MÃ');
        $spreadsheet->getActiveSheet()
                    ->getStyle("C5")
                    ->getFont()
                    ->setBold(true);

        $spreadsheet->getActiveSheet()
                    ->getStyle("C5")
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->setCellValue('D5', 'TRỌNG LƯỢNG (KG)');
        $spreadsheet->getActiveSheet()
                    ->getStyle("D5")
                    ->getFont()
                    ->setBold(true);

        $spreadsheet->getActiveSheet()
                    ->getStyle("D5")
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()
                    ->getStyle("D")
                    ->getNumberFormat()
                    ->setFormatCode('#,##0;[Red]-#,##0');
        $spreadsheet->getActiveSheet()
                    ->getStyle('B5:D5')
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFA500');

        //Fill the data
        $i = 6;
        foreach( $products as $row ) {
            foreach( range( 'B', 'D' ) as $v ) {
                switch( $v ) {
                    case 'B': {
                        $value = $i - 5;
                        break;
                    }
                    case 'C': {
                        $value = $row->code . ' ' . $row->detail;
                        break;
                    }
                    case 'D': {
                        $value = $row->quantity;
                        break;
                    }
                }
                $spreadsheet->getActiveSheet()->setCellValue( $v . $i, $value );
                $spreadsheet->getActiveSheet()
                            ->getStyle($v . $i)
                            ->getBorders()
                            ->getOutline()
                            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            }
            $i++;
        }
        $spreadsheet->getActiveSheet()->mergeCells("B".($i).":C".($i));
        $spreadsheet->getActiveSheet()->setCellValue( 'B' . $i, 'Tổng' );
        $spreadsheet->getActiveSheet()->setCellValue( 'D' . $i, $products->sum('quantity') );
        $spreadsheet->getActiveSheet()
                    ->getStyle("B".($i))
                    ->getFont()
                    ->setBold(true);

        $spreadsheet->getActiveSheet()
                    ->getStyle("B".($i))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()
                    ->getStyle("D".($i))
                    ->getFont()
                    ->setBold(true);

        $spreadsheet->getActiveSheet()
                    ->getStyle('B' . $i)
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()
                    ->getStyle('D' . $i)
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        //Save to file
        $writer = new Xlsx($spreadsheet);
        $file_name = 'Order-' . time() . '.xlsx';
        $writer->save($file_name);

        Session::flash('success_message', 'Tải file thành công!');
        return response()->download($file_name)->deleteFileAfterSend(true);
    }

    public function render()
    {
        $schedules = Schedule::orderBy('id', 'desc')->get();
        $schedule_id = $this->schedule_id;
        $level1_managers = User::where('role_id', 2)->get();
        $level1_manager_id = $this->level1_manager_id;

        switch(Auth::user()->role->name){
            case 'Admin':
            case 'Sản Xuất':
                //Get all products of all orders
                if('All' == $level1_manager_id){
                    $products = OrdersProducts::where('is_deleted', false)
                    ->join('products','orders_products.product_id', '=', 'products.id')
                    ->join('orders as order', function ($join) use ($schedule_id) {
                        $join->on('orders_products.order_id', '=', 'order.id')
                            ->where('order.status','=','Giám đốc đã duyệt')
                            ->where('order.schedule_id', '=', $schedule_id);
                    })
                    ->select('products.*', 'orders_products.*', DB::raw('sum(orders_products.quantity) AS quantity'))
                    ->groupBy('products.id')
                    ->get();
                }else{
                    //Filter by level1_manager_id
                    $products = OrdersProducts::where('is_deleted', false)
                    ->join('products','orders_products.product_id', '=', 'products.id')
                    ->join('orders as order', function ($join) use ($schedule_id, $level1_manager_id) {
                        $join->on('orders_products.order_id', '=', 'order.id')
                            ->where('order.status','=','Giám đốc đã duyệt')
                            ->where('order.schedule_id', '=', $schedule_id)
                            ->where('order.level1_manager_id', '=', $level1_manager_id);
                    })
                    ->select('products.*', 'orders_products.*', DB::raw('sum(orders_products.quantity) AS quantity'))
                    ->groupBy('products.id')
                    ->get();
                }
                break;
            case 'Giám đốc':
                //Get only the products of the orders that approved as level1_manager_id
                $products = OrdersProducts::where('is_deleted', false)
                ->join('products','orders_products.product_id', '=', 'products.id')
                ->join('orders as order', function ($join) use ($schedule_id) {
                    $join->on('orders_products.order_id', '=', 'order.id')
                        ->where('order.status','=','Giám đốc đã duyệt')
                        ->where('order.schedule_id', '=', $schedule_id)
                        ->where('order.level1_manager_id', '=', Auth::user()->id);
                })
                ->select('products.*', 'orders_products.*', DB::raw('sum(orders_products.quantity) AS quantity'))
                ->groupBy('products.id')
                ->get();
                break;
            case 'TV/GS':
                //Get only the products of the orders that approved as level2_manager_id
                $products = OrdersProducts::where('is_deleted', false)
                ->join('products','orders_products.product_id', '=', 'products.id')
                ->join('orders as order', function ($join) use ($schedule_id) {
                    $join->on('orders_products.order_id', '=', 'order.id')
                        ->where('order.status','=','Giám đốc đã duyệt')
                        ->where('order.schedule_id', '=', $schedule_id)
                        ->where('order.level2_manager_id', '=', Auth::user()->id);
                })
                ->select('products.*', 'orders_products.*', DB::raw('sum(orders_products.quantity) AS quantity'))
                ->groupBy('products.id')
                ->get();
                break;
            case 'Nhân viên':
                //Get only the products of the orders that creator as creator_id
                $products = OrdersProducts::where('is_deleted', false)
                ->join('products','orders_products.product_id', '=', 'products.id')
                ->join('orders as order', function ($join) use ($schedule_id) {
                    $join->on('orders_products.order_id', '=', 'order.id')
                        ->where('order.status','=','Giám đốc đã duyệt')
                        ->where('order.schedule_id', '=', $schedule_id)
                        ->where('order.creator_id', '=', Auth::user()->id);
                })
                ->select('products.*', 'orders_products.*', DB::raw('sum(orders_products.quantity) AS quantity'))
                ->groupBy('products.id')
                ->get();
                break;
        }
        return view('livewire.dashboard-component',
                    ['products' => $products,
                     'schedules' => $schedules,
                     'level1_managers' => $level1_managers
                    ])
                    ->layout('layouts.base');
    }
}
