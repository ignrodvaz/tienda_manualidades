<?php

namespace App\Controllers;

use App\Models\PedidoModel;

// Importar las clases necesarias para la generación del QR
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

//Importar las clases para generar PDF
use Dompdf\Dompdf;
use Dompdf\Options;

class PedidoController extends BaseController
{
    protected $PedidoModel;

    public function __construct()
    {
        helper(['form', 'url']);
        $this->PedidoModel = new PedidoModel();
    }

    public function index()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('login');
        }

        $rol = $session->get('rol');
        if ($rol !== 'ADMINISTRADOR' && $rol !== 'SUPERVISOR') {
            return redirect()->to('acceso_restringido');
        }

        $PedidoModel = new PedidoModel();

        $fecha_pedido = $this->request->getVar('FECHA_PEDIDO');
        $direccion_pedido = $this->request->getVar('DIRECCION_PEDIDO');
        $total_pedido = $this->request->getVar('TOTAL_PEDIDO');
        $estado_pedido = $this->request->getVar('ESTADO');
        $fk_id_cliente = $this->request->getVar('FK_ID_CLIENTE');
        $estado = $this->request->getGet('estado') ?? 'todas';
        $perPage = in_array($this->request->getGet('perPage'), [5, 10, 15, 20]) ? $this->request->getGet('perPage') : 10;

        $order_columna = trim($this->request->getGet('order_columna') ?? 'FECHA_PEDIDO');
        $order_direccion = trim($this->request->getGet('order_direccion') ?? 'asc');

        $query = $PedidoModel->select('PEDIDO.*, CLIENTE.NOMBRE AS CLIENTE_NOMBRE')->join('CLIENTE', 'CLIENTE.PK_ID_CLIENTE = PEDIDO.FK_ID_CLIENTE', 'left');

        switch ($estado) {
            case 'altas':
                $query->where('PEDIDO.FECHA_BAJA', null);
                break;
            case 'bajas':
                $query->where('PEDIDO.FECHA_BAJA !=', null);
                break;
            default:
                break;
        }

        if ($fecha_pedido) {
            $query->like('PEDIDO.FECHA_PEDIDO', $fecha_pedido);
        } else if ($direccion_pedido) {
            $query->like('PEDIDO.DIRECCION_PEDIDO', $direccion_pedido);
        } else if ($total_pedido) {
            $query->like('PEDIDO.TOTAL_PEDIDO', $total_pedido);
        } else if ($estado_pedido) {
            $query->like('PEDIDO.ESTADO', $estado_pedido);
        } else if ($fk_id_cliente) {
            $query->like('CLIENTE_NOMBRE', $fk_id_cliente);
        }

        $columnas_validas = ['FECHA_PEDIDO', 'DIRECCION_PEDIDO', 'TOTAL_PEDIDO', 'ESTADO', 'CLIENTE_NOMBRE'];

        if (in_array($order_columna, $columnas_validas) && in_array($order_direccion, ['asc', 'desc'])) {
            if ($order_columna === 'CLIENTE_NOMBRE') {
                $query->orderBy('CLIENTE.NOMBRE', $order_direccion);
            } else {
                $query->orderBy('PEDIDO.' . $order_columna, $order_direccion);
            }
        }

        $data['pedidos'] = $query->paginate($perPage);

        foreach ($data['pedidos'] as $pedido) {
            $pedido['FECHA_PEDIDO'] = date('Y-m-d', strtotime($pedido['FECHA_PEDIDO']));
        }

        $data['pager'] = $PedidoModel->pager;
        $data['fecha_pedido'] = $fecha_pedido;
        $data['direccion_pedido'] = $direccion_pedido;
        $data['total_pedido'] = $total_pedido;
        $data['estado_pedido'] = $estado_pedido;
        $data['fk_id_cliente'] = $fk_id_cliente;
        $data['estado'] = $estado;
        $data['perPage'] = $perPage;
        $data['order_columna'] = $order_columna;
        $data['order_direccion'] = $order_direccion;

        return view('listado_pedido', $data);
    }

    public function savePedido($PK_ID_PEDIDO = null)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('login');
        }

        $rol = $session->get('rol');
        if ($rol !== 'ADMINISTRADOR' && $rol !== 'SUPERVISOR') {
            return redirect()->to('acceso_restringido');
        }

        $PedidoModel = new PedidoModel();
        helper(['form', 'url']);

        $data['pedidos'] = $PedidoModel->findAll();
        $data['pedido'] = $PK_ID_PEDIDO ? $PedidoModel->find($PK_ID_PEDIDO) : null;

        if ($this->request->getMethod() == 'POST') {
            $rules = [
                'fecha_pedido' => 'required',
                'direccion_pedido' => 'required',
                'total_pedido' => 'required',
                'estado_pedido' => 'required',
            ];

            $messages = [
                'fecha_pedido' => ['required' => 'El campo Fecha Pedido es obligatorio.'],
                'direccion_pedido' => ['required' => 'El campo Dirección Pedido es obligatorio.'],
                'total_pedido' => ['required' => 'El campo Total Pedido es obligatorio.'],
                'estado_pedido' => ['required' => 'El campo Estado Pedido es obligatorio.'],
            ];

            if (!$this->validate($rules, $messages)) {
                $data['validation'] = $this->validator;
            } else {
                $PedidoData = [
                    'FECHA_PEDIDO' => $this->request->getPost('fecha_pedido'),
                    'DIRECCION_PEDIDO' => $this->request->getPost('direccion_pedido'),
                    'TOTAL_PEDIDO' => $this->request->getPost('total_pedido'),
                    'ESTADO' => $this->request->getPost('estado_pedido'),
                    'FK_ID_CLIENTE' => $this->request->getPost('fk_id_cliente'),
                ];

                if ($PK_ID_PEDIDO) {
                    $PedidoModel->update($PK_ID_PEDIDO, $PedidoData);
                    $message = 'Pedido actualizado correctamente.';
                } else {
                    $PedidoModel->save($PedidoData);
                    $message = 'Pedido creado correctamente.';
                }

                return redirect()->to('pedido')->with('success', $message);
            }
        }

        return view('crear_pedido', $data);
    }

    public function delete($PK_ID_PEDIDO)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('login');
        }

        $rol = $session->get('rol');
        if ($rol !== 'ADMINISTRADOR' && $rol !== 'SUPERVISOR') {
            return redirect()->to('acceso_restringido');
        }

        $PedidoModel = new PedidoModel();

        if ($PedidoModel->find($PK_ID_PEDIDO)['FECHA_BAJA'] === null) {
            $PedidoModel->update($PK_ID_PEDIDO, ['FECHA_BAJA' => date('Y-m-d H:i:s')]);
            return redirect()->to('/pedido')->with('success', 'Categoría dada de baja correctamente.');
        } else {
            $PedidoModel->update($PK_ID_PEDIDO, ['FECHA_BAJA' => NULL]);
            return redirect()->to('/pedido')->with('success', 'Categoría dada de alta correctamente.');
        }
    }

    public function exportar()
    {
        $fecha_pedido = $this->request->getVar('FECHA_PEDIDO');
        $direccion_pedido = $this->request->getVar('DIRECCION_PEDIDO');
        $total_pedido = $this->request->getVar('TOTAL_PEDIDO');
        $estado_pedido = $this->request->getVar('ESTADO');
        $fk_id_cliente = $this->request->getVar('FK_ID_CLIENTE');
        $estado = $this->request->getGet('estado') ?? 'todas';

        $order_columna = trim($this->request->getGet('order_columna') ?? 'FECHA_PEDIDO');
        $order_direccion = trim($this->request->getGet('order_direccion') ?? 'asc');

        $PedidoModel = new PedidoModel();

        $query = $PedidoModel->select('PEDIDO.*, CLIENTE.NOMBRE AS CLIENTE_NOMBRE')->join('CLIENTE', 'CLIENTE.PK_ID_CLIENTE = PEDIDO.FK_ID_CLIENTE', 'left');

        switch ($estado) {
            case 'altas':
                $query->where('PEDIDO.FECHA_BAJA', null);
                break;
            case 'bajas':
                $query->where('PEDIDO.FECHA_BAJA !=', null);
                break;
            default:
                break;
        }

        if ($fecha_pedido) {
            $query->like('PEDIDO.FECHA_PEDIDO', $fecha_pedido);
        } else if ($direccion_pedido) {
            $query->like('PEDIDO.DIRECCION_PEDIDO', $direccion_pedido);
        } else if ($total_pedido) {
            $query->like('PEDIDO.TOTAL_PEDIDO', $total_pedido);
        } else if ($estado_pedido) {
            $query->like('PEDIDO.ESTADO', $estado_pedido);
        } else if ($fk_id_cliente) {
            $query->like('CLIENTE_NOMBRE', $fk_id_cliente);
        }

        $query->orderBy('PEDIDO.' . $order_columna, $order_direccion);

        $pedidos = $query->findAll();
        error_log(print_r($pedidos, true));

        if (empty($pedidos)) {
            return "⚠️ No hay datos que coincidan con los filtros.";
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="Pedido_filtradas.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Fecha Pedido', 'Direccion', 'Total Pedido', 'Estado', 'Nombre Cliente', 'Fecha Baja', 'Fecha Creación', 'Fecha Actualización'], ';');

        foreach ($pedidos as $pedido) {
            fputcsv($output, [$pedido['FECHA_PEDIDO'], $pedido['DIRECCION_PEDIDO'], $pedido['TOTAL_PEDIDO'], $pedido['ESTADO'], $pedido['CLIENTE_NOMBRE'], $pedido['FECHA_BAJA'], $pedido['created_at'], $pedido['updated_at']], ';');
        }

        fclose($output);
        exit();
    }

    public function generarPDF($id_pedido)
    {
        $PedidoModel = new PedidoModel();
        
        // Obtener datos del pedido
        $pedido = $PedidoModel->select('PEDIDO.*, CLIENTE.NOMBRE AS CLIENTE_NOMBRE')
                            ->join('CLIENTE', 'CLIENTE.PK_ID_CLIENTE = PEDIDO.FK_ID_CLIENTE', 'left')
                            ->where('PEDIDO.PK_ID_PEDIDO', $id_pedido)
                            ->first();

        // Validar si el pedido existe
        if (!$pedido) {
            return redirect()->to('error_pedido_no_encontrado');
        }

        // Crear el contenido del PDF
        $html = '
            <h2>Pedido #'.$pedido['PK_ID_PEDIDO'].'</h2>
            <p><strong>Fecha:</strong> '.$pedido['FECHA_PEDIDO'].'</p>
            <p><strong>Dirección:</strong> '.$pedido['DIRECCION_PEDIDO'].'</p>
            <p><strong>Total:</strong> $'.$pedido['TOTAL_PEDIDO'].'</p>
            <p><strong>Estado:</strong> '.$pedido['ESTADO'].'</p>
            <p><strong>Cliente:</strong> '.$pedido['CLIENTE_NOMBRE'].'</p>
        ';

        // Configurar Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Configurar la descarga del PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="Pedido_'.$id_pedido.'.pdf"');

        echo $dompdf->output();
        exit();
    }


    public function generarQR($id_pedido)
    {
        // Generar la URL completa del PDF
        $baseUrl = base_url("pedido/generarPDF/$id_pedido");
    
        // Construcción del QR con la URL
        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: $baseUrl, // 📌 El QR ahora contiene la URL del PDF
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            logoPath: '',
            logoResizeToWidth: 50,
            logoPunchoutBackground: true,
            labelText: 'Escanea para descargar PDF',
            labelFont: new OpenSans(20),
            labelAlignment: LabelAlignment::Center
        );
    
        // Generar la imagen QR
        $result = $builder->build();
    
        // Configurar los encabezados para la descarga
        header('Content-Type: image/png');
        header('Content-Disposition: attachment; filename="QR_Pedido_' . $id_pedido . '.png"');
    
        // Enviar la imagen QR
        echo $result->getString();
        exit();
    }
    


}