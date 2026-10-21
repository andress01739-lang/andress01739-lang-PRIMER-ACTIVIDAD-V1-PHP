<?php

declare(strict_types=1);

// Clase abstracta Persona (Clase padre)
abstract class Persona
{
    protected string $tipoDocumento;
    protected string $numeroDocumento;
    protected string $nombres;
    protected string $apellidos;
    protected string $correo;

    public function __construct(
        string $tipoDocumento,
        string $numeroDocumento,
        string $nombres,
        string $apellidos,
        string $correo
    ) {
        $this->setTipoDocumento($tipoDocumento);
        $this->setNumeroDocumento($numeroDocumento);
        $this->setNombres($nombres);
        $this->setApellidos($apellidos);
        $this->setCorreo($correo);
    }

    public function getTipoDocumento(): string
    {
        return $this->tipoDocumento;
    }

    public function setTipoDocumento(string $tipoDocumento): void
    {
        $this->tipoDocumento = strtoupper(trim($tipoDocumento));
    }

    public function getNumeroDocumento(): string
    {
        return $this->numeroDocumento;
    }

    public function setNumeroDocumento(string $numeroDocumento): void
    {
        $this->numeroDocumento = trim($numeroDocumento);
    }

    public function getNombres(): string
    {
        return $this->nombres;
    }

    public function setNombres(string $nombres): void
    {
        $this->nombres = ucwords(strtolower(trim($nombres)));
    }

    public function getApellidos(): string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): void
    {
        $this->apellidos = ucwords(strtolower(trim($apellidos)));
    }

    public function getCorreo(): string
    {
        return $this->correo;
    }

    public function setCorreo(string $correo): void
    {
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("El formato del correo electrónico no es válido.");
        }
        $this->correo = strtolower(trim($correo));
    }

    abstract public function informacion(): string;
    abstract public function liquidarSalario(): float;
}

// Clase Funcionario (Empleado público)
class Funcionario extends Persona
{
    private float $salarioBasico;
    private int $diasTrabajados;
    private float $auxilioTransporteLegal;

    public function __construct(
        string $tipoDocumento,
        string $numeroDocumento,
        string $nombres,
        string $apellidos,
        string $correo,
        float $salarioBasico,
        int $diasTrabajados,
        float $auxilioTransporteLegal
    ) {
        parent::__construct($tipoDocumento, $numeroDocumento, $nombres, $apellidos, $correo);
        $this->setSalarioBasico($salarioBasico);
        $this->setDiasTrabajados($diasTrabajados);
        $this->setAuxilioTransporteLegal($auxilioTransporteLegal);
    }

    public function getSalarioBasico(): float
    {
        return $this->salarioBasico;
    }

    public function setSalarioBasico(float $salarioBasico): void
    {
        if ($salarioBasico < 0) {
            throw new InvalidArgumentException("El salario básico no puede ser negativo.");
        }
        $this->salarioBasico = $salarioBasico;
    }

    public function getDiasTrabajados(): int
    {
        return $this->diasTrabajados;
    }

    public function setDiasTrabajados(int $diasTrabajados): void
    {
        if ($diasTrabajados < 1 || $diasTrabajados > 30) {
            throw new InvalidArgumentException("Los días trabajados deben estar entre 1 y 30.");
        }
        $this->diasTrabajados = $diasTrabajados;
    }

    public function getAuxilioTransporteLegal(): float
    {
        return $this->auxilioTransporteLegal;
    }

    public function setAuxilioTransporteLegal(float $auxilioTransporteLegal): void
    {
        if ($auxilioTransporteLegal < 0) {
            throw new InvalidArgumentException("El auxilio de transporte no puede ser negativo.");
        }
        $this->auxilioTransporteLegal = $auxilioTransporteLegal;
    }

    public function informacion(): string
    {
        return sprintf(
            "--- FUNCIONARIO DE PLANTA ---\n" .
            "Documento: %s %s \n" .
            "Nombre completo: %s %s\n" .
            "Correo: %s\n" .
            "Salario Básico Mensual: $%.2f\n" .
            "Días Laborados: %d\n",
            $this->tipoDocumento,
            $this->numeroDocumento,
            $this->nombres,
            $this->apellidos,
            $this->correo,
            $this->salarioBasico,
            $this->diasTrabajados
        );
    }

    public function liquidarSalario(): float
    {
        $salarioProporcional = ($this->salarioBasico / 30) * $this->diasTrabajados;
        $auxilioProporcional = ($this->auxilioTransporteLegal / 30) * $this->diasTrabajados;

        $salud = $salarioProporcional * 0.04;
        $pension = $salarioProporcional * 0.04;

        $netoPagar = ($salarioProporcional + $auxilioProporcional) - ($salud + $pension);
        return round($netoPagar, 2);
    }
}

// Clase Contratista (Prestación de servicios)
class Contratista extends Persona
{
    private float $honorariosMensuales;
    private float $porcentajeRetencionFuente;

    public function __construct(
        string $tipoDocumento,
        string $numeroDocumento,
        string $nombres,
        string $apellidos,
        string $correo,
        float $honorariosMensuales,
        float $porcentajeRetencionFuente
    ) {
        parent::__construct($tipoDocumento, $numeroDocumento, $nombres, $apellidos, $correo);
        $this->setHonorariosMensuales($honorariosMensuales);
        $this->setPorcentajeRetencionFuente($porcentajeRetencionFuente);
    }

    public function getHonorariosMensuales(): float
    {
        return $this->honorariosMensuales;
    }

    public function setHonorariosMensuales(float $honorariosMensuales): void
    {
        if ($honorariosMensuales < 0) {
            throw new InvalidArgumentException("Los honorarios no pueden ser negativos.");
        }
        $this->honorariosMensuales = $honorariosMensuales;
    }

    public function getPorcentajeRetencionFuente(): float
    {
        return $this->porcentajeRetencionFuente;
    }

    public function setPorcentajeRetencionFuente(float $porcentajeRetencionFuente): void
    {
        $this->porcentajeRetencionFuente = $porcentajeRetencionFuente;
    }

    public function informacion(): string 
    {
        return sprintf(
            "--- CONTRATISTA (PRESTACIÓN DE SERVICIOS) ---\n" .
            "Documento: %s %s \n" .
            "Nombre completo: %s %s\n" .
            "Correo: %s\n" .
            "Honorarios Pactados: $%.2f\n" .
            "Retención en la fuente: %.1f%%\n",
            $this->tipoDocumento,
            $this->numeroDocumento,
            $this->nombres,
            $this->apellidos,
            $this->correo,
            $this->honorariosMensuales,
            $this->porcentajeRetencionFuente
        );
    }

    public function liquidarSalario(): float
    {
        $retencion = $this->honorariosMensuales * ($this->porcentajeRetencionFuente / 100);
        $netoPagar = $this->honorariosMensuales - $retencion;
        return round($netoPagar, 2);
    }
}

// Interfaz de consola interactiva
function leer(string $mensaje): string
{
    echo $mensaje;
    $resultado = readline();
    return trim($resultado !== false ? $resultado : '');
}

echo "\n====================================================\n";
echo "    SISTEMA DE LIQUIDACIÓN DE PERSONAL - ALCALDÍA    \n";
echo "====================================================\n\n";

echo "Seleccione el tipo de vinculación a registrar:\n";
echo "1. Funcionario de planta\n";
echo "2. Contratista\n";
$tipo = leer("Opción (1 o 2): ");

try {
    $tipoDoc = leer("Tipo de documento (CC, CE, NIT): ");
    $numDoc = leer("Número de documento: ");
    $nombres = leer("Nombres: ");
    $apellidos = leer("Apellidos: ");
    $correo = leer("Correo electrónico: ");

    if ($tipo === '1') {
        $salarioBasico = (float)leer("Salario básico mensual ($): ");
        $dias = (int)leer("Días trabajados en el mes (1-30): ");
        $auxTransporte = (float)leer("Valor auxilio de transporte legal vigente ($): ");
        
        $persona = new Funcionario(
            $tipoDoc, $numDoc, $nombres, $apellidos, $correo,
            $salarioBasico, $dias, $auxTransporte
        );
    } elseif ($tipo === '2') {
        $honorarios = (float)leer("Honorarios mensuales pactados ($): ");
        $retencionFuente = (float)leer("Porcentaje de retención en la fuente (%): ");

        $persona = new Contratista(
            $tipoDoc, $numDoc, $nombres, $apellidos, $correo,
            $honorarios, $retencionFuente
        );
    } else {
        throw new Exception("Opción de vinculación no válida.");
    }

    echo "\n==============================================\n";
    echo "Resultado del proceso de liquidación\n";
    echo "==============================================\n";
    echo $persona->informacion() . "\n";
    echo "Neto a pagar / Girar: $" . number_format($persona->liquidarSalario(), 2, ',', '.') . "\n";
    echo "==============================================\n\n";

} catch (Exception $e) {
    echo "\n[ERROR DE VALIDACIÓN]: " . $e->getMessage() . "\n\n";
}