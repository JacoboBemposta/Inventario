<?php 
use PHPUnit\Framework\TestCase;
use models\Bienes;
use models\EntradaBienes;
use models\Proveedor;
class BienesTest extends TestCase
{
    private $bienes;
    private $entradas;
    private $proveedores;

    protected function setUp(): void
    {
        // Usa la conexión real de la base de datos de pruebas
        $this->bienes = new Bienes(DB::connect());
        $this->entradas = new EntradaBienes(DB::connect());
        $this->proveedores = new Proveedor(DB::connect());
    }


    public function testObtenerPorEntradaIdExistente(){
        // Insertamos un proveedor y obtenemos su ID
        $this->proveedores->agregarProveedor('Proveedor de Prueba');
        $proveedorId = $this->entradas->getDbConnection()->lastInsertId();
    
        // Insertamos una entrada y obtenemos su ID
        $this->entradas->agregarEntrada('Entrada de Prueba', '12345', $proveedorId, '2024-10-01', '2024-10-01', 10, 1000, '12345');
        $entradaBienId = $this->entradas->getDbConnection()->lastInsertId();
    
        // Insertamos bienes relacionados con el ID de entrada obtenido
        $this->bienes->agregarBien('Bien 1', '1.00', '1', 'SIGA', 'OR', $entradaBienId);
        $this->bienes->agregarBien('Bien 2', '1.00', '1', 'Administracion', 'ME', $entradaBienId);
    
        // Obtenemos los bienes relacionados a esa entrada
        $resultados = $this->bienes->obtenerPorEntradaId($entradaBienId);
    
        // Definimos el array esperado con el ID real de entrada
        $resultadoEsperados = [
            ['descripcion' => 'Bien 1', 'precio' => '1.00', 'centro' => '1', 'departamento' => 'SIGA', 'tipo_bien' => 'OR', 'entrada_bien_id' => (string)$entradaBienId, 'estado' => '1', 'activo' => '1'],
            ['descripcion' => 'Bien 2', 'precio' => '1.00', 'centro' => '1', 'departamento' => 'Administracion', 'tipo_bien' => 'ME', 'entrada_bien_id' => (string)$entradaBienId, 'estado' => '1', 'activo' => '1'],
        ];
    
        // Eliminamos campos no necesarios para la comparación
        foreach ($resultados as &$resultado) {
            unset($resultado['id']);
            unset($resultado['fecha_baja']);
            unset($resultado['causa_baja']);
            unset($resultado['fecha_alta']);
        }
    
        // Comparamos los resultados
        $this->assertEquals($resultadoEsperados, $resultados);
    }
    
    public function testObtenerPorEntradaIdInexistente(){

        $entrada_bien_id_inexistente = 999; // Un ID que sabemos no existe en la base de datos
    
        $resultados = $this->bienes->obtenerPorEntradaId($entrada_bien_id_inexistente);
    
        $resultadoEsperados = []; // Suponemos que el método devuelve un array vacío si no encuentra resultados
    
        $this->assertEquals($resultadoEsperados, $resultados, "Se esperaba un array vacío al no encontrar el ID de entrada.");
    }

    public function testObtenerBienExistente(){
        // Insertamos un proveedor
        $this->proveedores->agregarProveedor('Proveedor de Prueba');
        $proveedorId = $this->bienes->getDbConnection()->lastInsertId();
        
        // Insertamos una entrada
        $this->entradas->agregarEntrada('Entrada de Prueba', '12345', $proveedorId, '2024-10-01', '2024-10-01', 10, 1000, '12345');
        $entrada_bien_id = $this->bienes->getDbConnection()->lastInsertId();  
 
        $this->bienes->agregarBien('Bien de Prueba', '1.00', '1', 'SIGA', 'OR', $entrada_bien_id); 

        $bienId = $this->bienes->getDbConnection()->lastInsertId();
        
        // Llama al método obtenerBienPorId con el ID recién insertado
        $resultado = $this->bienes->obtenerBien($bienId);
    
        $resultadoEsperado = [
            'descripcion' => 'Bien de Prueba',
            'precio' => '1.00',
            'centro' => '1',
            'departamento' => 'SIGA',
            'tipo_bien' => 'OR',
            'entrada_bien_id' => $entrada_bien_id,
            'estado' => '1',
            'activo' => '1',
        ];

        unset($resultado['id']);
        unset($resultado['fecha_baja']);
        unset($resultado['causa_baja']);
        unset($resultado['fecha_alta']);
        $this->assertEquals($resultadoEsperado, $resultado);
    }

    public function testObtenerBienInExistente(){
        $idInexistente = 999999; 
        $resultado = $this->bienes->obtenerBien($idInexistente);

        $this->assertFalse($resultado, "Se esperaba null al no encontrar el ID en la base de datos.");
    }
    public function testObtenerPorIdExistente(){
        // Insertamos un proveedor
        $this->proveedores->agregarProveedor('Proveedor de Prueba');
        $proveedorId = $this->bienes->getDbConnection()->lastInsertId();
        
        // Insertamos una entrada
        $this->entradas->agregarEntrada('Entrada de Prueba', '12345', $proveedorId, '2024-10-01', '2024-10-01', 10.00, 1000, '12345');
        

        $entradaBienId = $this->bienes->getDbConnection()->lastInsertId();

        // Insertamos un bien 
        $this->bienes->agregarBien('Bien de Prueba', '1.00', '1', 'SIGA', 'OR', $entradaBienId);
        
        $bienId = $this->bienes->getDbConnection()->lastInsertId();
    
        // Ejecuta el método y verifica el resultado
        $resultado = $this->bienes->obtenerPorId($bienId);

        // Resultado esperado, ajustado para coincidencias exactas
        $resultadoEsperado = [
            'descripcion' => 'Bien de Prueba',
            'precio' => '1.00',
            'centro' => '1',
            'departamento' => 'SIGA',
            'tipo_bien' => 'OR',
            'entrada_bien_id' => (string) $entradaBienId,
            'estado' => '1',
            'activo' => '1',
            'nombre' => 'Entrada de Prueba',
            'entradaID' => (string) $entradaBienId,
            'cuenta_contable' => '12345',
            'fecha_inicio_amortizacion' => '2024-10-01',
            'numero_factura' => '12345',
            'fecha_compra' => '2024-10-01',
            'proveedorID' => (string) $proveedorId
        ];
    
 
        unset($resultado['id']);
        unset($resultado['fecha_baja']);
        unset($resultado['causa_baja']);
        unset($resultado['porcentaje_amortizacion']);
        unset($resultado['fecha_alta']);
        $this->assertEquals($resultadoEsperado, $resultado);
    }
    

    public function testObtenerPorIdInexistente(){
        $idInexistente = 999999; 
        $resultado = $this->bienes->obtenerPorId($idInexistente);

        $this->assertFalse($resultado, "Se esperaba null al no encontrar el ID en la base de datos.");
    }


    public function testbuscarfiltro() {
        // Inserta un proveedor
        $this->proveedores->agregarProveedor('Proveedor de Prueba');
        $proveedorId = $this->bienes->getDbConnection()->lastInsertId();
        
        // Inserta una entrada
        $this->entradas->agregarEntrada('Entrada de Prueba', '12345', $proveedorId, '2024-10-01', '2024-10-01', 10.00, 1000, '12345');
        $entradaBienId = $this->bienes->getDbConnection()->lastInsertId();
        
        // Inserta un bien 
        $this->bienes->getDbConnection()->exec("
            INSERT INTO bienes (descripcion, precio, centro, departamento, tipo_bien, fecha_alta, entrada_bien_id, estado, activo)
            VALUES ('Bien de Prueba', '1.00', '1', 'SIGA', 'OR', '2024-10-19', $entradaBienId, 1, 1)
        ");
    
        // Ejecuta el método con todos los filtros
        $resultado = $this->bienes->buscarfiltro('1', 'SIGA', 'OR', '1', '2024-10-18', '2024-10-20', 'Bien', '12345');
        

    
        // Resultado esperado
        $resultadoEsperado = [
            'descripcion' => 'Bien de Prueba',
            'precio' => '1.00',
            'centro' => '1',
            'departamento' => 'SIGA',
            'tipo_bien' => 'OR',
            'fecha_alta' => '2024-10-19',
            'entrada_bien_id' => (string) $entradaBienId,
            'estado' => '1',
            'activo' => '1',
            'cuenta_contable' => '12345',
        ];
    
        // comprobamos que el resultado no venga vacio
        $this->assertNotEmpty($resultado, "El método buscarfiltro no devolvió ningún resultado.");
    
        // recorremos el primer elemento del array
        $resultado = $resultado[0];
    
        unset($resultado['id'], $resultado['fecha_baja'], $resultado['causa_baja']);
    
        // Verifica que el resultado coincida con lo esperado
        $this->assertEquals($resultadoEsperado, $resultado);
    }
    
    public function testbuscarfiltroinexistente() {
        // Inserta un proveedor
        $this->proveedores->agregarProveedor('Proveedor de Prueba');
        $proveedorId = $this->bienes->getDbConnection()->lastInsertId();
        
        // Inserta una entrada
        $this->entradas->agregarEntrada('Entrada de Prueba', '12345', $proveedorId, '2024-10-01', '2024-10-01', 10.00, 1000, '12345');
        $entradaBienId = $this->bienes->getDbConnection()->lastInsertId();
        
        // Inserta un bien 
        $this->bienes->agregarBien('Bien de Prueba', '1.00', '1', 'SIGA', 'OR', $entradaBienId);
        // Ejecuta el método con todos los filtros
        $resultado = $this->bienes->buscarfiltro('1', 'SIGA', 'OR', '1', '2024-10-18', '2024-10-20', 'Bien', '54321');
        

    
        // Resultado esperado
        $resultadoEsperado = [
            'descripcion' => 'Bien de Prueba',
            'precio' => '1.00',
            'centro' => '1',
            'departamento' => 'SIGA',
            'tipo_bien' => 'OR',
            'entrada_bien_id' => (string) $entradaBienId,
            'estado' => '1',
            'activo' => '1',
            'cuenta_contable' => '12345',
        ];

        // comprobamos que el resultado venga vacio
        $this->assertEmpty($resultado, "El método buscarfiltro no devolvió ningún resultado.");
    }

    
    public function testobtenerBienes(){
        // Insertamos un proveedor y obtenemos su ID
        $this->proveedores->agregarProveedor('Proveedor de Prueba');
        $proveedorId = $this->entradas->getDbConnection()->lastInsertId();
    
        // Insertamos una entrada y obtenemos su ID
        $this->entradas->agregarEntrada('Entrada de Prueba', '12345', $proveedorId, '2024-10-01', '2024-10-01', 10, 1000, '12345');
        $entradabienid = $this->entradas->getDbConnection()->lastInsertId();

        // Insertamos bienes relacionados con el ID de entrada obtenido
        $this->bienes->agregarBien('Bien 1', '1.00', '1', 'SIGA', 'OR', $entradabienid);
        $this->bienes->agregarBien('Bien 2', '1.00', '1', 'Administracion', 'ME', $entradabienid);
        $resultados = $this->bienes->obtenerBienes();
    
        $resultadoEsperados = [
            ['descripcion' => 'Bien 1', 'precio' => '1.00', 'centro' => '1', 'departamento' => 'SIGA', 'tipo_bien' => 'OR', 'entrada_bien_id' => $entradabienid, 'estado' => '1', 'activo' => '1'],
            ['descripcion' => 'Bien 2', 'precio' => '1.00', 'centro' => '1', 'departamento' => 'Administracion', 'tipo_bien' => 'ME', 'entrada_bien_id' => $entradabienid, 'estado' => '1', 'activo' => '1'],
        ];
    

        foreach ($resultados as &$resultado) {
            unset($resultado['id']); 
            unset($resultado['fecha_baja']); 
            unset($resultado['causa_baja']);
            unset($resultado['fecha_alta']);
        }
        
        
        $this->assertEquals($resultadoEsperados, $resultados);        
    }

    public function testAgregarBienExitoso(){    
        // Insertamos un proveedor y obtenemos su ID
        $this->proveedores->agregarProveedor('Proveedor de Prueba');
        $proveedorId = $this->entradas->getDbConnection()->lastInsertId();
    
        // Insertamos una entrada y obtenemos su ID
        $this->entradas->agregarEntrada('Entrada de Prueba', '12345', $proveedorId, '2024-10-01', '2024-10-01', 10, 1000, '12345');
        $entradaBienId = $this->entradas->getDbConnection()->lastInsertId();
    

        $descripcion = 'Bien de Prueba';
        $precio = '100.00';
        $centro = '1';
        $departamento = 'SIGA';
        $tipo_bien = 'OR';

        $resultado = $this->bienes->agregarBien($descripcion, $precio, $centro, $departamento, $tipo_bien, $entradaBienId);

        // Verifica que la inserción fue exitosa
        $this->assertTrue($resultado, "Se esperaba que la inserción del bien fuera exitosa.");

        // Verifica que el bien se haya insertado correctamente
        $stmt = $this->bienes->getDbConnection()->query("SELECT * FROM bienes WHERE descripcion = '$descripcion'");
        $bien = $stmt->fetch(\PDO::FETCH_ASSOC);

        $this->assertNotFalse($bien, "Se esperaba que el bien fuera encontrado en la base de datos.");
        $this->assertEquals($descripcion, $bien['descripcion']);
        $this->assertEquals($precio, $bien['precio']);
        $this->assertEquals($centro, $bien['centro']);
        $this->assertEquals($departamento, $bien['departamento']);
        $this->assertEquals($tipo_bien, $bien['tipo_bien']);
        $this->assertEquals($entradaBienId, $bien['entrada_bien_id']);
    }

    public function testAgregarBienFallaSinEntradaBienId(){

        $descripcion = 'Bien de Prueba Sin Entrada';
        $precio = '150.00';
        $centro = '1';
        $departamento = 'SIGA';
        $tipo_bien = 'OR';
        $entrada_bien_id_inexistente = null; // ID que no existe
  
        // Se espera que se lance una excepción de tipo InvalidArgumentException
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("EL bien debe estar asociado a un ID de entrada correcto"); // Opcional
        // Intentar agregar el bien
        $resultado = $this->bienes->agregarBien($descripcion, $precio, $centro, $departamento, $tipo_bien, $entrada_bien_id_inexistente);
        $this->assertFalse($resultado,'Se esperaba que no agrege un bien sin entrada');
    }
    public function testAgregarBienFallaConEntradaBienIdInexistente(){

        $descripcion = 'Bien de Prueba Con Entrada Inexistente';
        $precio = '150.00';
        $centro = '1';
        $departamento = 'SIGA';
        $tipo_bien = 'OR';
        $entrada_bien_id_inexistente = 9999; 
    
        // Se espera que se lance una excepción de tipo PDOException
        $this->expectException(\PDOException::class);
        
  
        $resultado=$this->bienes->agregarBien($descripcion, $precio, $centro, $departamento, $tipo_bien, $entrada_bien_id_inexistente);

        $this->assertFalse($resultado,'Se esperaba que no agrege un bien sin entrada');
    }
        

    public function testEditarBienExitoso(){
        // Insertamos un proveedor
        $this->proveedores->agregarProveedor('Proveedor de Prueba');
        $proveedorId = $this->bienes->getDbConnection()->lastInsertId();
        
        // Insertamos una entrada
        $this->entradas->agregarEntrada('Entrada de Prueba', '12345', $proveedorId, '2024-10-01', '2024-10-01', 10.00, 1000, '12345');
        $entradaBienId = $this->bienes->getDbConnection()->lastInsertId();
      
        // Insertar un bien para editar
        $this->bienes->agregarBien('Bien Original', '1.00', '1', 'SIGA', 'OR', $entradaBienId);
        $bienId = $this->bienes->getDbConnection()->lastInsertId();
      

        // Datos para la edición
        $descripcionNueva = 'Bien Editado';
        $precioNuevo = '150.00';
        $centroNuevo = '2';
        $departamentoNuevo = 'FINANZAS';
        $tipoBienNuevo = 'PR';
        $causaBajaNueva = null; // Asumimos que no hay causa de baja
    
        // Ejecutamos el método
        $resultado = $this->bienes->editarBien($bienId, $descripcionNueva, $precioNuevo, $centroNuevo, $departamentoNuevo, $tipoBienNuevo, $causaBajaNueva);
    
        // Verificamos que el método devolvió true (edición exitosa)
        $this->assertTrue($resultado);
    
        // Obtener el bien editado
        $bienEditado = $this->bienes->obtenerBien($bienId);

        // Verificamos que los cambios se reflejan
        $this->assertEquals($descripcionNueva, $bienEditado['descripcion']);
        $this->assertEquals($precioNuevo, $bienEditado['precio']);
        $this->assertEquals($centroNuevo, $bienEditado['centro']);
        $this->assertEquals($departamentoNuevo, $bienEditado['departamento']);
        $this->assertEquals($tipoBienNuevo, $bienEditado['tipo_bien']);
    }
    
    public function testEditarBienNoExiste(){
        $idInexistente = 999999; // ID que no existe
        $descripcionNueva = 'Bien Inexistente';
        $precioNuevo = '150.00';
        $centroNuevo = '2';
        $departamentoNuevo = 'FINANZAS';
        $tipoBienNuevo = 'PR';
        $causaBajaNueva = null;
    
        // Ejecutar el método
        $resultado = $this->bienes->editarBien($idInexistente, $descripcionNueva, $precioNuevo, $centroNuevo, $departamentoNuevo, $tipoBienNuevo, $causaBajaNueva);
    
        // Verificar que el método devolvió false (no se realizó ninguna actualización)
        $this->assertFalse($resultado, "Se esperaba que el método devolviera false al intentar editar un bien que no existe.");
    }
    
    public function testEditarBienInactivo(){
        // Insertamos un proveedor
        $this->bienes->getDbConnection()->exec("INSERT INTO proveedores (nombre) VALUES ('Proveedor de Prueba')");
        $proveedorId = $this->bienes->getDbConnection()->lastInsertId();
        
        // Insertamos una entrada
        $this->entradas->agregarEntrada('Entrada de Prueba', '12345', $proveedorId, '2024-10-01', '2024-10-01', 10.00, 1000, '12345');
        $entradaBienId = $this->bienes->getDbConnection()->lastInsertId();
      
        // Insertar un bien para editar (usamos el comando sql por no poder agregar un bien inactivo)
        $this->bienes->getDbConnection()->exec("
            INSERT INTO bienes (descripcion, precio, centro, departamento, tipo_bien, fecha_alta, entrada_bien_id, activo)
            VALUES ('Bien Inactivo', '100.00', '1', 'SIGA', 'OR', NOW(), $entradaBienId, 0)
        ");
        $bienId = $this->bienes->getDbConnection()->lastInsertId();
    
        // Datos para la edición
        $descripcionNueva = 'Bien Editado';
        $precioNuevo = '150.00';
        $centroNuevo = '2';
        $departamentoNuevo = 'FINANZAS';
        $tipoBienNuevo = 'PR';
        $causaBajaNueva = null;
    
        // Ejecutar el método
        $resultado = $this->bienes->editarBien($bienId, $descripcionNueva, $precioNuevo, $centroNuevo, $departamentoNuevo, $tipoBienNuevo, $causaBajaNueva);
    
        // Verificar que el método devolvió false (no se realizó ninguna actualización)
        $this->assertFalse($resultado);
    
        // Obtener el bien inactivo
        $bienInactivo = $this->bienes->getDbConnection()->query("SELECT * FROM bienes WHERE id = $bienId")->fetch(PDO::FETCH_ASSOC);
    
        // Verificar que los datos no han cambiado
        $this->assertEquals('Bien Inactivo', $bienInactivo['descripcion']);
        $this->assertEquals('100.00', $bienInactivo['precio']);
    }
    
    public function testeliminarBien(){
        // Insertamos un proveedor
        $this->proveedores->agregarProveedor('Proveedor de Prueba');
        $proveedorId = $this->bienes->getDbConnection()->lastInsertId();
        
        // Insertamos una entrada
        $this->entradas->agregarEntrada('Entrada de Prueba', '12345', $proveedorId, '2024-10-01', '2024-10-01', 10.00, 1000, '12345');
        $entradaBienId = $this->bienes->getDbConnection()->lastInsertId();
      
        // Insertar un bien para editar
        $this->bienes->agregarBien('Bien Inactivo', '100.00', '1', 'SIGA', 'OR',  $entradaBienId);
        $bienId = $this->bienes->getDbConnection()->lastInsertId();
        
        $resultado=$this->bienes->eliminarBien($bienId);

        $this->AssertTrue($resultado,'Se ha eliminado el bien');
    } 

    public function testeliminarbienInexistente(){
        $bienId = 99999;
        // Ejecutar el método
        $resultado = $this->bienes->eliminarBien($bienId);
    
        // Verificar que el método devolvió false (no se realizó ninguna actualización)
        $this->assertFalse($resultado, "Se esperaba que el método devolviera false al intentar editar un bien que no existe.");
    }
    
    public function testactualizarestado(){
        // Insertamos un proveedor
        $this->proveedores->agregarProveedor('Proveedor de Prueba');
        $proveedorId = $this->bienes->getDbConnection()->lastInsertId();
        
        // Insertamos una entrada
        $this->entradas->agregarEntrada('Entrada de Prueba', '12345', $proveedorId, '2024-10-01', '2024-10-01', 10.00, 1000, '12345');
        $entradaBienId = $this->bienes->getDbConnection()->lastInsertId();
    
        // Insertar bienes (usamos SQL para poder indicar campo activo)
        $this->bienes->getDbConnection()->exec("
            INSERT INTO bienes (descripcion, precio, centro, departamento, tipo_bien, fecha_alta, entrada_bien_id, activo, estado)
            VALUES ('Bien 1 Activo', '100.00', '1', 'SIGA', 'OR', NOW(), $entradaBienId, 1, 1)  -- estado 1 representa Activo
        ");
        $bienId1 = $this->bienes->getDbConnection()->lastInsertId();
    
        $this->bienes->getDbConnection()->exec("
            INSERT INTO bienes (descripcion, precio, centro, departamento, tipo_bien, fecha_alta, entrada_bien_id, activo, estado)
            VALUES ('Bien 2 Inactivo', '100.00', '1', 'SIGA', 'OR', NOW(), $entradaBienId, 0, 0)  -- estado 0 representa Inactivo
        ");        
        $bienId2 = $this->bienes->getDbConnection()->lastInsertId();
    
        // Actualizar estado de los bienes
        $resultado = $this->bienes->actualizaEstado('Averiado', 0, $bienId1); // Cambiamos a estado 0 (Inactivo)
        $resultado2 = $this->bienes->actualizaEstado(null, 1, $bienId2); // Cambiamos a estado 1 (Activo)
    
        // Comprobar resultados
        $this->assertTrue($resultado, 'Se ha actualizado el bien 1 a inactivo');
        $this->assertTrue($resultado2, 'Se ha actualizado el bien 2 a activo');
    
        // Verificar los cambios en la base de datos
        $stmt1 = $this->bienes->getDbConnection()->prepare("SELECT estado FROM bienes WHERE id = ?");
        $stmt1->execute([$bienId1]);
        $estadoBien1 = $stmt1->fetchColumn();
    
        $stmt2 = $this->bienes->getDbConnection()->prepare("SELECT estado FROM bienes WHERE id = ?");
        $stmt2->execute([$bienId2]);
        $estadoBien2 = $stmt2->fetchColumn();
    
        $this->assertEquals(0, $estadoBien1, 'El estado del bien 1 debería ser 0 (Inactivo) después de la actualización.');
        $this->assertEquals(1, $estadoBien2, 'El estado del bien 2 debería ser 1 (Activo) después de la actualización.');
    }
    
    public function testCambiarEstadoANull(){
        // Insertamos un proveedor
        $this->proveedores->agregarProveedor('Proveedor de Prueba');
        $proveedorId = $this->bienes->getDbConnection()->lastInsertId();
        
        // Insertamos una entrada
        $this->entradas->agregarEntrada('Entrada de Prueba', '12345', $proveedorId, '2024-10-01', '2024-10-01', 10.00, 1000, '12345');
        $entradaBienId = $this->bienes->getDbConnection()->lastInsertId();
    
        // Insertar un bien activo
        $this->bienes->agregarBien('Bien Activo', '1.00', '1', 'SIGA', 'OR', $entradaBienId);

        $bienId = $this->bienes->getDbConnection()->lastInsertId();
    
        // Intentar cambiar el estado a null
        $resultado = $this->bienes->actualizaEstado(null, null, $bienId);
    
        // Verificar que el método devolvió false
        $this->assertFalse($resultado, 'No se debería permitir cambiar el estado a null.');
        
        // Verificar el estado del bien en la base de datos no cambió
        $stmt = $this->bienes->getDbConnection()->prepare("SELECT estado FROM bienes WHERE id = ?");
        $stmt->execute([$bienId]);
        $estadoBien = $stmt->fetchColumn();
        
        // El estado debería seguir siendo 1 (Activo)
        $this->assertEquals(1, $estadoBien, 'El estado del bien debería seguir siendo 1 (Activo) después de intentar cambiar a null.');
    }
        
    public function testCambiarEstadoAValorAleatorio(){
        // Insertamos un proveedor
        $this->proveedores->agregarProveedor('Proveedor de Prueba');
        $proveedorId = $this->bienes->getDbConnection()->lastInsertId();
        
        // Insertamos una entrada
        $this->entradas->agregarEntrada('Entrada de Prueba', '12345', $proveedorId, '2024-10-01', '2024-10-01', 10.00, 1000, '12345');
        $entradaBienId = $this->bienes->getDbConnection()->lastInsertId();
    
        // Insertar un bien activo
        $this->bienes->agregarBien('Bien de Prueba', '1.00', '1', 'SIGA', 'OR', $entradaBienId);
        $bienId = $this->bienes->getDbConnection()->lastInsertId();
    
        // Intentar cambiar el estado a un valor aleatorio (como 99)
        $resultado = $this->bienes->actualizaEstado(null, 99, $bienId);
    
        // Verificar que el método devolvió false
        $this->assertFalse($resultado, 'No se debería permitir cambiar el estado a un valor no permitido (como 99).');
        
        // Verificar el estado del bien en la base de datos no cambió
        $stmt = $this->bienes->getDbConnection()->prepare("SELECT estado FROM bienes WHERE id = ?");
        $stmt->execute([$bienId]);
        $estadoBien = $stmt->fetchColumn();
        
        // El estado debería seguir siendo 1 (Activo)
        $this->assertEquals(1, $estadoBien, 'El estado del bien debería seguir siendo 1 (Activo) después de intentar cambiar a un valor no permitido.');
    }
       
    protected function tearDown(): void
    {
        // Limpia la tabla de bienes después de cada prueba
        $this->bienes->getDbConnection()->exec("DELETE FROM bienes");
        $this->bienes->getDbConnection()->exec("DELETE FROM entradas_bienes");
        $this->bienes->getDbConnection()->exec("DELETE FROM proveedores");
    }

}


?>