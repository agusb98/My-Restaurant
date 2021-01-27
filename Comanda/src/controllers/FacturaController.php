<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include_once("./pdf/FPDF/fpdf.php");

class FacturaController{
    public function getExcel($pedido_codigo){
        $pedido = PedidoController::getOne($pedido_codigo);
        $excel = new Spreadsheet();
        $excel->getProperties()
        ->setCreator("Agustin Baez")
        ->setTitle("Factura")
        ->setDescription("Factura");
    
        $excel->setActiveSheetIndex(0);
        $excel->getActiveSheet()
        ->getColumnDimension('A')
        ->setAutoSize(true);
    
        $excel->getActiveSheet()
        ->getColumnDimension('B')
        ->setAutoSize(true);
    
        $excel->getActiveSheet()
        ->getColumnDimension('C')
        ->setAutoSize(true);
    
        $excel->getActiveSheet()->setTitle("Factura");
    
        $excel->getActiveSheet()->setCellValue("A1","Codigo");
        $excel->getActiveSheet()->setCellValue("B1",$pedido_codigo);
        $excel->getActiveSheet()->setCellValue("A3","Cantidad");
        $excel->getActiveSheet()->setCellValue("B3","Producto");
        $excel->getActiveSheet()->setCellValue("C3","Precio");
    
        $count = 4;
        foreach (ItemController::getGroup($pedido_codigo) as $item) 
        {
            $excel->getActiveSheet()->setCellValue("A$count", 1);
            $excel->getActiveSheet()->setCellValue("B$count", $item->nombre);
            $excel->getActiveSheet()->setCellValue("C$count", $item->precio);
            $count++;
        }
        $count++;
        $importe = ItemController::acumImport($pedido_codigo);
    
        $excel->getActiveSheet()->setCellValue("A$count","Total");
        $excel->getActiveSheet()->setCellValue("C$count", $importe);
        
        $count++;
        $excel->getActiveSheet()->setCellValue("A$count", 'Fecha');
        $excel->getActiveSheet()->setCellValue("B$count", $pedido->created_at);
    
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename-"./excel/"' . $pedido_codigo . '.xlsx');
        header('Cache-Control: max-age=0');
    
        $writer = new Xlsx($excel);
        return $writer->save("./excel/" . $pedido_codigo . '.xlsx');
    }

    public function getPDF($pedido_codigo){
        if($pedido = PedidoController::getOne($pedido_codigo)){
            //NO ENCUENTRA CLASE FPDF
            $pdf = new FPDF("P","mm","A4");
            $pdf->AddPage();
            $pdf->SetFont("Arial","B",12);
    
            $pdf->Cell(50,10,'Fecha',1,0,"C");
            $pdf->Cell(50,10,'Codigo Mesa',1,0,"C");
            $pdf->Cell(50,10,'Importe',1,1,"C");
            
            $importe = ItemController::acumImport($pedido_codigo);
            
            $pdf->Cell(50,10, $pedido->created_at, 1, 0, "C");
            $pdf->Cell(50,10, $pedido->mesa_codigo, 1, 0, "C");
            $pdf->Cell(50,10,"$". $importe, 1, 1, "C");
    
            $pdf->Output("F","./pdf/" . $pedido_codigo . ".pdf",true);
            
            $rta = array("Estado" => "OK", "Mensaje" => "PDF generado correctamente.");
        }
        else{ $rta = array("Estado" => "ERROR", "Mensaje" => "Pedido no encontrado"); }

        $response->getBody()->write(json_encode($rta));
        return $response;
    }
}
