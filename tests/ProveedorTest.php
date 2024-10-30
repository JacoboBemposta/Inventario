<?php 

use PHPUnit\Framework\TestCase;
use models\Proveedor;


class ProveedorTest extends TestCase
{
    private $proveedores;


    protected function setUp(): void
    {
        // Usa la conexión real de la base de datos de pruebas
 
        $this->proveedores = new Proveedor(DB::connect());
 
    }   


    public function testObtenerTodosconExito(){
        //Insertamos 2 proveedores
        $this->proveedores->agregarProveedor('Proveedor 1');
        $proveedorId1 = $this->proveedores->getDbConnection()->lastInsertId();

        $this->proveedores->agregarProveedor('Proveedor 2');
        $proveedorId2 = $this->proveedores->getDbConnection()->lastInsertId();
                
        $resultados = $this->proveedores->obtenerTodos();

            // Verificamos que se obtienen los nombres de los proveedores correctamente
            $this->assertCount(2, $resultados, 'Se deben obtener dos entradas activas.');
            $this->assertEquals('Proveedor 1', $resultados[0]['nombre']);
            $this->assertEquals('Proveedor 2', $resultados[1]['nombre']);        
    }


    public function testObtenerTodosNoInactivo(){
        //Insertamos 2 proveedores
        $this->proveedores->agregarProveedor('Proveedor 1');
        $proveedorId1 = $this->proveedores->getDbConnection()->lastInsertId();

        $this->proveedores->agregarProveedor('Proveedor 2');
        $proveedorId2 = $this->proveedores->getDbConnection()->lastInsertId();
            
        //Insertamos 1 proveedor inactivo
        $this->proveedores->getDbConnection()->exec("INSERT INTO proveedores (nombre,activo) VALUES ('Proveedor Inactivo','0')");
        $proveedorId3 = $this->proveedores->getDbConnection()->lastInsertId();        

        $resultados = $this->proveedores->obtenerTodos();

            // Verificamos que se obtienen los nombres de los proveedores correctamente
            $this->assertCount(2, $resultados, 'Se deben obtener dos proveedores activos.');
            $this->assertEquals('Proveedor 1', $resultados[0]['nombre']);
            $this->assertEquals('Proveedor 2', $resultados[1]['nombre']);             
    }

    public function testObtenerUno(){
        // Insertamos un proveedor
        $this->proveedores->getDbConnection()->exec("INSERT INTO proveedores (nombre) VALUES ('Proveedor 1')");
        $proveedorId = $this->proveedores->getDbConnection()->lastInsertId();    
        $resultado = $this->proveedores->obtenerUno($proveedorId);   
        
        $this->assertNotNull($resultado, 'Se debe obtener un proveedor activo.');
        $this->assertEquals('Proveedor 1', $resultado['nombre']);
    }

    public function testObtenerUnoConIdInactivo()
    {

        // Insertamos un proveedor inactivo
        $this->proveedores->getDbConnection()->exec("INSERT INTO proveedores (nombre,activo) VALUES ('Proveedor 1','0')");
        
        $proveedorId = $this->proveedores->getDbConnection()->lastInsertId();    

        $resultado = $this->proveedores->obtenerUno($proveedorId);   

        // Verificamos que no se retorne nada
        $this->assertFalse($resultado, 'No se debe obtener un proveedor inactivo.');
    }

    public function testObtenerUnoConIdNoExistente(){
   
        $resultado = $this->proveedores->obtenerUno(99999);   

        // Verificamos que no se retorne nada
        $this->assertFalse($resultado, 'No se debe obtener un proveedor inactivo.');

    }

    public function testAgregarProveedor(){
        $this->proveedores->agregarProveedor('Proveedor 1');
        $proveedorId = $this->proveedores->getDbConnection()->lastInsertId();   
        
        $resultado=$this->proveedores->obtenerUno($proveedorId);

        $this->assertNotNull($resultado, 'Se debe obtener un proveedor activa.');
        $this->assertEquals('Proveedor 1', $resultado['nombre']);        
    }

    public function testAgregarProveedornull(){

            //Insertamos un proveedor
            $resultado = $this->proveedores->agregarProveedor('');
    
            $this->assertFalse($resultado, 'El proveedor no debe ser agregado con datos faltantes.');    
        
    }


    public function testeditarProveedor(){
        //Insertamos un proveedor
        $this->proveedores->agregarProveedor('Proveedor 1');
        $proveedorId = $this->proveedores->getDbConnection()->lastInsertId(); 
        
        $this->proveedores->editarProveedor($proveedorId,'Proveedor Editado');
        
        $resultado = $this->proveedores->obtenerUno($proveedorId);

        $this->assertEquals('Proveedor Editado', $resultado['nombre']);   

    }

    public function testeditarProveedorInexistente(){

        $resultado = $this->proveedores->editarProveedor(99999 , 'Proveedor Editado');        ;

        $this->assertFalse($resultado, 'No se debe obtener un proveedor inexistente.');  
    }

    public function testeliminarProveedor(){
        //Insertamos 2 proveedores
        $this->proveedores->agregarProveedor('Proveedor 1');
        $proveedorId1 = $this->proveedores->getDbConnection()->lastInsertId();

        $this->proveedores->agregarProveedor('Proveedor 2');
        $proveedorId2 = $this->proveedores->getDbConnection()->lastInsertId();

        //Eliminamos 1 proveedor
        $this->proveedores->eliminarProveedor($proveedorId2);

        //Obtenemos todos los proveedores restantes (deberia ser 1)        
        $resultados = $this->proveedores->obtenerTodos();

            // Verificamos que se obtienen los nombres de los proveedores correctamente
            $this->assertCount(1, $resultados, 'Se deben obtener un proveedor activo.');
            $this->assertEquals('Proveedor 1', $resultados[0]['nombre']);         
    }

    public function testeliminarProveedorInexistente(){

        $resultado = $this->proveedores->eliminarProveedor(99999);

        $this->assertFalse($resultado, 'No se debe obtener un proveedor que no existe.');

    }
    protected function tearDown(): void
    {
        // Limpia la tabla de bienes después de cada prueba
        $this->proveedores->getDbConnection()->exec("DELETE FROM bienes");
        $this->proveedores->getDbConnection()->exec("DELETE FROM entradas_bienes");
        $this->proveedores->getDbConnection()->exec("DELETE FROM proveedores");
    }    
}