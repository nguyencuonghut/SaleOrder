<?php

namespace App\Livewire;

use App\Models\OrdersProducts;
use App\Models\Product;
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

        //Set font
        $styleArray = array(
            'font'  => array(
                 'name'  => 'Times New Roman'
             ),
        );
        $spreadsheet->getDefaultStyle()
                    ->applyFromArray($styleArray);

        //Create the first worksheet
        $w_sheet = $spreadsheet->getActiveSheet();
        $w_sheet->setTitle("Tổng hợp");
        //Fill the first worksheet
        $this->fillFirstWorkSheet($w_sheet, $products, $schedule);

        //Create the second workshhet
        $spreadsheet->createSheet();
        $w_sheet = $spreadsheet->setActiveSheetIndex(1)->setTitle('Nhà phân phối');
        //Fill the second worksheet
        $product_ids = Product::where('category_id', 3)->where('status', 'Kích hoạt')->pluck('id')->toArray();
        $second_products = $products->whereIn('product_id', $product_ids);
        $this->fillOtherWorkSheet($w_sheet, $second_products, $schedule);

        //Create the 3rd workshhet
        $spreadsheet->createSheet();
        $w_sheet = $spreadsheet->setActiveSheetIndex(2)->setTitle('Trại gia công');
        //Fill the 3rd worksheet
        $product_ids = Product::where('category_id', 1)->where('status', 'Kích hoạt')->pluck('id')->toArray();
        $second_products = $products->whereIn('product_id', $product_ids);
        $this->fillOtherWorkSheet($w_sheet, $second_products, $schedule);

        //Create the 4th workshhet
        $spreadsheet->createSheet();
        $w_sheet = $spreadsheet->setActiveSheetIndex(3)->setTitle('Hàng đặt riêng');
        //Fill the 3rd worksheet
        $product_ids = Product::where('category_id', 2)->where('status', 'Kích hoạt')->pluck('id')->toArray();
        $second_products = $products->whereIn('product_id', $product_ids);
        $this->fillOtherWorkSheet($w_sheet, $second_products, $schedule);

        //Create the 5th workshhet
        $spreadsheet->createSheet();
        $w_sheet = $spreadsheet->setActiveSheetIndex(4)->setTitle('Hàng Silo');
        //Fill the 3rd worksheet
        $product_ids = Product::where('category_id', 4)->where('status', 'Kích hoạt')->pluck('id')->toArray();
        $second_products = $products->whereIn('product_id', $product_ids);
        $this->fillOtherWorkSheet($w_sheet, $second_products, $schedule);


        //Set active worksheet to 0
        $spreadsheet->setActiveSheetIndex(0);

        //Save to file
        $writer = new Xlsx($spreadsheet);
        $file_name = 'Order-' . time() . '.xlsx';
        $writer->save($file_name);

        Session::flash('success_message', 'Tải file thành công!');
        return response()->download($file_name)->deleteFileAfterSend(true);
    }

    private function fillFirstWorkSheet($w_sheet, $products, $schedule)
    {
        //Set column width
        $w_sheet->getColumnDimension('A')->setWidth(3);
        $w_sheet->getColumnDimension('C')->setWidth(30);
        $w_sheet->getColumnDimension('D')->setWidth(30);

        //Fill the title
        $w_sheet->setCellValue('B2', 'KẾ HOẠCH ĐẶT HÀNG SẢN XUẤT');
        $w_sheet->getStyle("B2")
                    ->getFont()
                    ->setSize(13)
                    ->setBold(true);

        $w_sheet->mergeCells("B2:D2");
        $w_sheet->mergeCells("B3:D3");
        $w_sheet->getStyle('B2')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $w_sheet->getStyle('B3')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $w_sheet->setCellValue('B3', $schedule->title);
        $w_sheet->getStyle("B3")
                    ->getFont()
                    ->setSize(13)
                    ->setBold(true);

        //Fill the column names
        $w_sheet->setCellValue('B5', 'STT');
        $w_sheet->getStyle("B5")
                    ->getFont()
                    ->setBold(true);

        $w_sheet->getStyle("B5")
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $w_sheet->setCellValue('C5', 'MÃ');
        $w_sheet->getStyle("C5")
                    ->getFont()
                    ->setBold(true);

        $w_sheet->getStyle("C5")
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $w_sheet->setCellValue('D5', 'TRỌNG LƯỢNG (KG)');
        $w_sheet->getStyle("D5")
                    ->getFont()
                    ->setBold(true);

        $w_sheet->getStyle("D5")
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $w_sheet->getStyle("D")
                    ->getNumberFormat()
                    ->setFormatCode('#,##0;[Red]-#,##0');
        $w_sheet->getStyle('B5:D5')
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFA500');

        $w_sheet->getStyle('B5:D5')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        //Fill the data
        $i = 6;
        foreach( $products as $product ) {
            foreach( range( 'B', 'D' ) as $v ) {
                switch( $v ) {
                    case 'B': {
                        $value = $i - 5;
                        break;
                    }
                    case 'C': {
                        $value = $product->code . ' ' . $product->detail;
                        break;
                    }
                    case 'D': {
                        $value = $product->quantity;
                        break;
                    }
                }
                $w_sheet->setCellValue( $v . $i, $value );
                $w_sheet->getStyle($v . $i)
                            ->getBorders()
                            ->getOutline()
                            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            }
            $i++;
        }
        $w_sheet->mergeCells("B".($i).":C".($i));
        $w_sheet->setCellValue( 'B' . $i, 'Tổng' );
        $w_sheet->setCellValue( 'D' . $i, $products->sum('quantity') );
        $w_sheet->getStyle("B".($i))
                    ->getFont()
                    ->setBold(true);

        $w_sheet->getStyle("B".($i))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $w_sheet->getStyle("D".($i))
                    ->getFont()
                    ->setBold(true);

        $w_sheet->getStyle('B' . $i)
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $w_sheet->getStyle('D' . $i)
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    }


    private function fillOtherWorkSheet($w_sheet, $products, $schedule)
    {
        //Set column width
        $w_sheet->getColumnDimension('A')->setWidth(3);
        $w_sheet->getColumnDimension('C')->setWidth(30);
        $w_sheet->getColumnDimension('D')->setWidth(30);

        //Fill the column names
        $w_sheet->setCellValue('B2', 'STT');
        $w_sheet->getStyle("B2")
                    ->getFont()
                    ->setBold(true);

        $w_sheet->getStyle("B2")
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $w_sheet->setCellValue('C2', 'MÃ');
        $w_sheet->getStyle("C2")
                    ->getFont()
                    ->setBold(true);

        $w_sheet->getStyle("C2")
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $w_sheet->setCellValue('D2', 'TRỌNG LƯỢNG (KG)');
        $w_sheet->getStyle("D2")
                    ->getFont()
                    ->setBold(true);

        $w_sheet->getStyle("D2")
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $w_sheet->getStyle("D")
                    ->getNumberFormat()
                    ->setFormatCode('#,##0;[Red]-#,##0');
        $w_sheet->getStyle('B2:D2')
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFA500');

        $w_sheet->getStyle('B2:D2')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        //Fill the data
        $i = 3;
        //Get all products in category = 3 (Nhà phân phối)
        foreach( $products as $product ) {
            foreach( range( 'B', 'D' ) as $v ) {
                switch( $v ) {
                    case 'B': {
                        $value = $i - 2;
                        break;
                    }
                    case 'C': {
                        $value = $product->code . ' ' . $product->detail;
                        break;
                    }
                    case 'D': {
                        $value = $product->quantity;
                        break;
                    }
                }
                $w_sheet->setCellValue( $v . $i, $value );
                $w_sheet->getStyle($v . $i)
                            ->getBorders()
                            ->getOutline()
                            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            }
            $i++;
        }
        $w_sheet->mergeCells("B".($i).":C".($i));
        $w_sheet->setCellValue( 'B' . $i, 'Tổng' );
        $w_sheet->setCellValue( 'D' . $i, $products->sum('quantity') );
        $w_sheet->getStyle("B".($i))
                    ->getFont()
                    ->setBold(true);

        $w_sheet->getStyle("B".($i))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $w_sheet->getStyle("D".($i))
                    ->getFont()
                    ->setBold(true);

        $w_sheet->getStyle('B' . $i)
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $w_sheet->getStyle('D' . $i)
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
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
