<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class HorarioSucursalModel extends Model
{
    protected $table = 'horario_sucursal';
    protected $primaryKey = 'idHorario';

    protected $allowedFields = [
        'diaSemana',
        'horaApertura',
        'horaCierre',
        'idSucursal'   
    ];

    public function haySucursalAbierta(): bool
    {
        // Obtener la fecha y hora actual de Mendoza
        $ahora = Time::now('America/Argentina/Mendoza');

        // Convertir el día al formato utilizado en la base de datos
        $dias = [
            'Monday'    => 'Lunes',
            'Tuesday'   => 'Martes',
            'Wednesday' => 'Miercoles',
            'Thursday'  => 'Jueves',
            'Friday'    => 'Viernes',
            'Saturday'  => 'Sabado',
            'Sunday'    => 'Domingo'
        ];

        $diaActual = $dias[$ahora->format('l')];
        $horaActual = $ahora->format('H:i');

        // Consultar los horarios de las sucursales activas
        $horarios = $this
            ->select(
                'horario_sucursal.horaApertura,
                 horario_sucursal.horaCierre'
            )
            ->join(
                'sucursal',
                'sucursal.idSucursal = horario_sucursal.idSucursal'
            )
            ->where('sucursal.activo', 1)
            ->where('horario_sucursal.diaSemana', $diaActual)
            ->findAll();

        // Verificar si alguna sucursal está atendiendo
        foreach ($horarios as $horario) {
            $apertura = substr($horario['horaApertura'], 0, 5);
            $cierre = substr($horario['horaCierre'], 0, 5);

            if (
                $horaActual >= $apertura &&
                $horaActual <= $cierre
            ) {
                return true;
            }
        }

        return false;
    }
}