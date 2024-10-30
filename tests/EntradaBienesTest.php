<?php 

use PHPUnit\Framework\TestCase;
use models\EntradaBienes;
use models\Bienes;

class EntradaBienesTest extends TestCase
{
    private $entradas;
    private $bienes;
    private $db;

    protected function setUp(): void
    {
        // Usa la conexión real de la base de datos de pruebas
 
        $this->entradas = new EntradaBienes(DB::connect());
        $this->bienes = new Bienes(DB::connect());
    }   

    public function testObtenerTodas(){
        // Insertamos un proveedor
        $this->entradas->getDbConnection()->exec("INSERT INTO proveedores (nombre) VALUES ('Proveedor de Prueba')");
        
        $proveedorId = $this->entradas->getDbConnection()->lastInsertId();
        $this->assertNotEmpty($proveedorId, 'El ID del proveedor no debe estar vacío.');
        

        // Insertamos entradas activas y una inactiva
        $this->entradas->getDbConnection()->exec("
            INSERT INTO entradas_bienes (descripcion, numero_factura, proveedor_id , fecha_compra, fecha_inicio_amortizacion, porcentaje_amortizacion, precio, cuenta_contable, activo)
            VALUES 
            ('Entrada Activa 1', '12345', $proveedorId, '2024-10-01','2024-10-01', '10', 529, '12345', 1),
            ('Entrada Activa 2', '54321', $proveedorId, '2024-10-01', '2024-10-01','20', 229, '12345', 1),
            ('Entrada Inactiva 3', '54321', $proveedorId, '2024-10-01', '2024-10-01','20', 229, '12345', 0)
        ");
    

    
        // Ejecutamos el método obtenerTodas
        $resultados = $this->entradas->obtenerTodas();
    
        // Verificamos que el número de resultados es 2 (solo las activas)
        $this->assertCount(2, $resultados, 'Se deben obtener solo las entradas activas.');
    
        // Verificamos que los resultados contengan las entradas correctas
        $this->assertEquals('Entrada Activa 1', $resultados[0]['descripcion']);
        $this->assertEquals('Entrada Activa 2', $resultados[1]['descripcion']);
    }
    
    public function testObtenerTodasConProveedores(){
        // Insertamos dos proveedores
        $this->entradas->getDbConnection()->exec("INSERT INTO proveedores (nombre) VALUES ('Proveedor 1')");
        $proveedorId1 = $this->entradas->getDbConnection()->lastInsertId();

        $this->entradas->getDbConnection()->exec("INSERT INTO proveedores (nombre) VALUES ('Proveedor 2')");
        $proveedorId2 = $this->entradas->getDbConnection()->lastInsertId();
        
            // Insertamos entradas activas con diferentes proveedores
            $this->entradas->getDbConnection()->exec("
                INSERT INTO entradas_bienes (descripcion, numero_factura, proveedor_id , fecha_compra, fecha_inicio_amortizacion, porcentaje_amortizacion, precio, cuenta_contable, activo)
                VALUES 
                ('Entrada Activa 1', '12345', $proveedorId1, '2024-10-01','2024-10-01', '10', 529, '12345', 1),
                ('Entrada Activa 2', '54321', $proveedorId2, '2024-10-01', '2024-10-01','20', 229, '12345', 1)
            ");
        
            // Ejecutamos el método obtenerTodas
            $resultados = $this->entradas->obtenerTodas();
        
            // Verificamos que se obtienen los nombres de los proveedores correctamente
            $this->assertCount(2, $resultados, 'Se deben obtener dos entradas activas.');
            $this->assertEquals('Proveedor 1', $resultados[0]['proveedor_nombre']);
            $this->assertEquals('Proveedor 2', $resultados[1]['proveedor_nombre']);
    }
    
    public function testObtenerTodasNoInactivas(){
        // Insertamos un proveedor
        $this->entradas->getDbConnection()->exec("INSERT INTO proveedores (nombre) VALUES ('Proveedor de Prueba')");
        $proveedorId = $this->entradas->getDbConnection()->lastInsertId();
    
        // Insertamos entradas activas
        $this->entradas->getDbConnection()->exec("
            INSERT INTO entradas_bienes (descripcion, numero_factura, proveedor_id , fecha_compra, fecha_inicio_amortizacion, porcentaje_amortizacion, precio, cuenta_contable, activo)
            VALUES 
            ('Entrada Activa 1', '12345', $proveedorId, '2024-10-01','2024-10-01', '10', 529, '12345', 1),
            ('Entrada Activa 2', '54321', $proveedorId, '2024-10-01', '2024-10-01','20', 229, '12345', 1)
        ");
    
        // Insertamos una entrada inactiva
        $this->entradas->getDbConnection()->exec("
            INSERT INTO entradas_bienes (descripcion, numero_factura, proveedor_id , fecha_compra, fecha_inicio_amortizacion, porcentaje_amortizacion, precio, cuenta_contable, activo)
            VALUES         
            ('Entrada Activa 3', '12345', $proveedorId, '2024-10-01','2024-10-01', '10', 529, '12345', 0)
        ");
    
        // Ejecutamos el método obtenerTodas
        $resultados = $this->entradas->obtenerTodas();
    
        // Verificamos que el número de resultados es 1 (solo la activa)
        $this->assertCount(2, $resultados, 'Se deben obtener solo las entradas activas.');
        $this->assertEquals('Entrada Activa 1', $resultados[0]['descripcion']);
        $this->assertEquals('Entrada Activa 2', $resultados[1]['descripcion']);
    }
    

    public function testObtenerUnoConIdActivo(){
        // Insertamos un proveedor
        $this->entradas->getDbConnection()->exec("INSERT INTO proveedores (nombre) VALUES ('Proveedor de Prueba')");
        $proveedorId = $this->entradas->getDbConnection()->lastInsertId();

        // Insertamos una entrada activa
        $this->entradas->getDbConnection()->exec("
            INSERT INTO entradas_bienes (descripcion, numero_factura, proveedor_id, fecha_compra, fecha_inicio_amortizacion, porcentaje_amortizacion, precio, cuenta_contable, activo)
            VALUES 
            ('Entrada Activa', '12345', $proveedorId, '2024-10-01', '2024-10-01', '10', 529, '12345', 1)
        ");

        $entrada_bien_id=$this->entradas->getDbConnection()->lastInsertId();

        // Intentamos obtener la entrada activa
        $resultado = $this->entradas->obtenerUno($entrada_bien_id); 

        // Verificamos que se retorne la entrada correcta
        $this->assertNotNull($resultado, 'Se debe obtener una entrada activa.');
        $this->assertEquals('Entrada Activa', $resultado['descripcion']);
    }

    public function testObtenerUnoConIdInactivo(){
        // Insertamos un proveedor
        $this->entradas->getDbConnection()->exec("INSERT INTO proveedores (nombre) VALUES ('Proveedor de Prueba')");
        $proveedorId = $this->entradas->getDbConnection()->lastInsertId();

        // Insertamos una entrada inactiva
        $this->entradas->getDbConnection()->exec("
            INSERT INTO entradas_bienes (descripcion, numero_factura, proveedor_id, fecha_compra, fecha_inicio_amortizacion, porcentaje_amortizacion, precio, cuenta_contable, activo)
            VALUES 
            ('Entrada Inactiva', '54321', $proveedorId, '2024-10-01', '2024-10-01', '10', 529, '12345', 0)
        ");

        $entrada_bien_id=$this->entradas->getDbConnection()->lastInsertId();

        // Intentamos obtener la entrada inactiva
        $resultado = $this->entradas->obtenerUno($entrada_bien_id); // Suponiendo que el ID insertado es 2

        // Verificamos que no se retorne nada
        $this->assertFalse($resultado, 'No se debe obtener una entrada inactiva.');
    }

    public function testObtenerUnoConIdNoExistente(){
        // Intentamos obtener una entrada con un ID que no existe
        $resultado = $this->entradas->obtenerUno(999); 
        // Verificamos que no se retorne nada
        $this->assertFalse($resultado, 'No se debe obtener una entrada con un ID no existente.');
    }
    
    public function testAgregarEntradaCorrectamente(){
        // Insertamos un proveedor
        $this->entradas->getDbConnection()->exec("INSERT INTO proveedores (nombre) VALUES ('Proveedor de Prueba')");
        $proveedorId = $this->entradas->getDbConnection()->lastInsertId();

        // Llamamos al método agregarEntrada
        $resultado = $this->entradas->agregarEntrada('Entrada Activa', '12345', $proveedorId, '2024-10-01', '2024-10-01', 10, 1000, '12345');

        // Verificamos que el resultado sea true (inserción exitosa)
        $this->assertTrue($resultado, 'La entrada debe ser agregada correctamente.');

        // Verificamos que la entrada se haya agregado a la base de datos
        $entradaAgregada = $this->entradas->getDbConnection()->query("SELECT * FROM entradas_bienes WHERE descripcion = 'Entrada Activa'")->fetch(\PDO::FETCH_ASSOC);
        $this->assertNotFalse($entradaAgregada, 'La entrada debe existir en la base de datos.');
        $this->assertEquals('Entrada Activa', $entradaAgregada['descripcion']);
    }

    public function testAgregarEntradaConDatosInvalidos(){
        // Llamamos al método agregarEntrada con datos inválidos
        $resultado = $this->entradas->agregarEntrada('', '', null, '2024-10-01', '2024-10-01', -10, -1000, ''); // Campos vacíos y valores inválidos

        // Verificamos que el resultado sea false (inserción fallida)
        $this->assertFalse($resultado, 'La entrada no debe ser agregada con datos inválidos.');
    }
    
    
    public function testAgregarEntradaConDatosFaltantes(){
        // Insertamos un proveedor
        $this->entradas->getDbConnection()->exec("INSERT INTO proveedores (nombre) VALUES ('Proveedor de Prueba')");
        $proveedorId = $this->entradas->getDbConnection()->lastInsertId();

        // Intentamos agregar una entrada con campos vacíos
        $resultado = $this->entradas->agregarEntrada('', '12345', $proveedorId, '', '', 10, 1000, '12345');

        // Verificamos que el resultado sea false (inserción fallida)
        $this->assertFalse($resultado, 'La entrada no debe ser agregada con datos faltantes.');
    }

    public function testAgregarEntradaConPrecioNegativo(){
        // Insertamos un proveedor
        $this->entradas->getDbConnection()->exec("INSERT INTO proveedores (nombre) VALUES ('Proveedor de Prueba')");
        $proveedorId = $this->entradas->getDbConnection()->lastInsertId();

        // Intentamos agregar una entrada con un precio negativo
        $resultado = $this->entradas->agregarEntrada('Entrada Activa', '12345', $proveedorId, '2024-10-01', '2024-10-01', 10, -1000, '12345');

        // Verificamos que el resultado sea false (inserción fallida)
        $this->assertFalse($resultado, 'La entrada no debe ser agregada con un precio negativo.');
    }    
    
    public function testEditarEntradaCorrectamente(){
        // Insertamos un proveedor
        $this->entradas->getDbConnection()->exec("INSERT INTO proveedores (nombre) VALUES ('Proveedor de Prueba')");
        $proveedorId = $this->entradas->getDbConnection()->lastInsertId();

        // Insertamos una entrada inicial
        $this->entradas->agregarEntrada('Entrada Original', '12345', $proveedorId, '2024-10-01', '2024-10-01', 10, 1000, '12345');
        $entradaId = $this->entradas->getDbConnection()->lastInsertId();

        // Editamos la entrada
        $resultado = $this->entradas->editarEntrada($entradaId, 'Entrada Editada', '54321', $proveedorId, '2024-11-01', '2024-11-01', 15, 1500, '54321');

        // Verificamos que el resultado sea true (actualización exitosa)
        $this->assertTrue($resultado, 'La entrada debe ser editada correctamente.');

        // Verificamos que los cambios se hayan aplicado
        $entradaEditada = $this->entradas->getDbConnection()->query("SELECT * FROM entradas_bienes WHERE id = $entradaId")->fetch(\PDO::FETCH_ASSOC);
        $this->assertNotFalse($entradaEditada, 'La entrada editada debe existir en la base de datos.');
        $this->assertEquals('Entrada Editada', $entradaEditada['descripcion']);
    }

    public function testEditarEntradaInexistente(){
        // Intentamos editar una entrada que no existe
        $resultado = $this->entradas->editarEntrada(99999, 'Entrada Inexistente', '99999', null, '2024-10-01', '2024-10-01', 10, 1000, '12345');

        // Verificamos que el resultado sea false (no se puede editar una entrada inexistente)
        $this->assertFalse($resultado, 'No se debe poder editar una entrada que no existe.');
    }

    public function testEditarEntradaConDatosInvalidos(){
        // Insertamos un proveedor
        $this->entradas->getDbConnection()->exec("INSERT INTO proveedores (nombre) VALUES ('Proveedor de Prueba')");
        $proveedorId = $this->entradas->getDbConnection()->lastInsertId();

        // Insertamos una entrada inicial
        $this->entradas->agregarEntrada('Entrada Original', '12345', $proveedorId, '2024-10-01', '2024-10-01', 10, 1000, '12345');
        $entradaId = $this->entradas->getDbConnection()->lastInsertId();

        // Intentamos editar la entrada con datos inválidos
        $resultado = $this->entradas->editarEntrada($entradaId, '', '', $proveedorId, '', '', -10, -1000, '');

        // Verificamos que el resultado sea false (actualización fallida)
        $this->assertFalse($resultado, 'La entrada no debe ser editada con datos inválidos.');
    }

    public function testEditarEntradaInactiva(){
        // Insertamos un proveedor
        $this->entradas->getDbConnection()->exec("INSERT INTO proveedores (nombre) VALUES ('Proveedor de Prueba')");
        $proveedorId = $this->entradas->getDbConnection()->lastInsertId();

        // Insertamos una entrada activa
        $this->entradas->agregarEntrada('Entrada Activa', '12345', $proveedorId, '2024-10-01', '2024-10-01', 10, 1000, '12345');
        $entradaId = $this->entradas->getDbConnection()->lastInsertId();

        // Marcamos la entrada como inactiva
        $this->entradas->getDbConnection()->exec("UPDATE entradas_bienes SET activo = 0 WHERE id = $entradaId");

        // Intentamos editar la entrada inactiva
        $resultado = $this->entradas->editarEntrada($entradaId, 'Entrada Inactiva Editada', '54321', $proveedorId, '2024-11-01', '2024-11-01', 15, 1500, '54321');

        // Verificamos que el resultado sea false (no se puede editar una entrada inactiva)
        $this->assertFalse($resultado, 'No se debe poder editar una entrada inactiva.');
    }


    public function testEliminarEntradaCorrectamente(){
        // Insertamos un proveedor
        $this->entradas->getDbConnection()->exec("INSERT INTO proveedores (nombre) VALUES ('Proveedor de Prueba')");
        $proveedorId = $this->entradas->getDbConnection()->lastInsertId();
    
        // Insertamos una entrada
        $this->entradas->agregarEntrada('Entrada a Eliminar', '12345', $proveedorId, '2024-10-01', '2024-10-01', 10, 1000, '12345');
        $entradaId = $this->entradas->getDbConnection()->lastInsertId();
    
        // Insertamos bienes relacionados con la entrada
        $this->bienes->agregarBien('Bien 1', 125, '1', 'SIGA', 'OR', $entradaId);
        $this->bienes->agregarBien('Bien 2', 250, '2', 'Administracion', 'OR', $entradaId);
    
        // Ejecutamos el método eliminarEntrada
        $resultado = $this->entradas->eliminarEntrada($entradaId);
    
        // Verificamos que el resultado sea true (eliminación exitosa)
        $this->assertTrue($resultado, 'La entrada debe ser eliminada correctamente.');
    
        // Verificamos que la entrada esté inactiva
        $entradaEliminada = $this->entradas->getDbConnection()->query("SELECT * FROM entradas_bienes WHERE id = $entradaId")->fetch(\PDO::FETCH_ASSOC);
        $this->assertNotFalse($entradaEliminada, 'La entrada eliminada debe existir en la base de datos.');
        $this->assertEquals(0, $entradaEliminada['activo'], 'La entrada debe estar marcada como inactiva.');
    
        // Verificamos que todos los bienes relacionados también estén inactivos
        $bienesInsertados = $this->bienes->getDbConnection()->query("SELECT * FROM bienes WHERE entrada_bien_id = $entradaId")->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertNotEmpty($bienesInsertados, 'Debe haber bienes relacionados con la entrada.');
    
        foreach ($bienesInsertados as $bien) {
            $this->assertEquals(0, $bien['activo'], 'Cada bien relacionado debe estar marcado como inactivo.');
        }
    }
    

    public function testEliminarEntradaInexistente(){
        // Intentamos eliminar una entrada que no existe
        $resultado = $this->entradas->eliminarEntrada(9999);

        // Verificamos que el resultado sea false (no se puede eliminar una entrada inexistente)
        $this->assertFalse($resultado, 'No se debe poder eliminar una entrada que no existe.');
    }


    
    protected function tearDown(): void
    {
        // Limpia la tabla de entradas después de cada prueba
        $this->entradas->getDbConnection()->exec("DELETE FROM entradas_bienes");
        $this->entradas->getDbConnection()->exec("DELETE FROM proveedores");
        $this->entradas->getDbConnection()->exec("DELETE FROM bienes");
    }

}
