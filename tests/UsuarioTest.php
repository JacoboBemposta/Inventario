<?php 

use PHPUnit\Framework\TestCase;
use models\Usuario;


class UsuarioTest extends TestCase
{
    private $usuarios;


    protected function setUp(): void
    {
        // Usa la conexión real de la base de datos de pruebas
 
        $this->usuarios = new Usuario(DB::connect());
 
    }   


    public function testObtenerTodosconExito(){
        //Insertamos 2 usuarios
        $this->usuarios->agregarUsuario('Usuario 1','user1','1234','ADMIN','prueba1@gmail.com');
        $ID1 = $this->usuarios->getDbConnection()->lastInsertId();

        $this->usuarios->agregarUsuario('Usuario 2','user2','1234','EMPLEADO','prueba2@gmail.com');
        $ID2 = $this->usuarios->getDbConnection()->lastInsertId();
                
        $resultados = $this->usuarios->obtenerTodos();

            // Verificamos que se obtienen los nombres de los usuarios correctamente
            $this->assertCount(2, $resultados, 'Se deben obtener dos entradas activas.');
            $this->assertEquals('Usuario 1', $resultados[0]['nombre']);
            $this->assertEquals('Usuario 2', $resultados[1]['nombre']);        
    }

    public function testObtenerTodosNoInactivo(){
        //Insertamos 2 usuarios
        $this->usuarios->agregarUsuario('Usuario 1','user1','1234','ADMIN','prueba@gmail.com');
        $ID1 = $this->usuarios->getDbConnection()->lastInsertId();

        $this->usuarios->agregarUsuario('Usuario 2','user2','1234','EMPLEADO','prueba2@gmail.com');
        $ID2 = $this->usuarios->getDbConnection()->lastInsertId();
            
        //Insertamos 1 proveedor inactivo
        $this->usuarios->getDbConnection()->exec("INSERT INTO usuarios (nombre,usuario,contrasena,tipo_usuario,activo) VALUES ('Usuario Inactivo','user3','1234','ADMIN','0')");
        $ID3 = $this->usuarios->getDbConnection()->lastInsertId();        

        $resultados = $this->usuarios->obtenerTodos();

            // Verificamos que se obtienen los nombres de los usuarios correctamente
            $this->assertCount(2, $resultados, 'Se deben obtener dos usuarios activos.');
            $this->assertEquals('Usuario 1', $resultados[0]['nombre']);
            $this->assertEquals('Usuario 2', $resultados[1]['nombre']);             
    }

    public function testObtenerUno(){
        // Insertamos un usuario activo
        $this->usuarios->agregarUsuario('Usuario 1', 'user1', '1234', 'ADMIN','prueba@gmail.com');
        $ID = $this->usuarios->getDbConnection()->lastInsertId();    
        $resultado = $this->usuarios->obtenerUno($ID);   
        
        $this->assertNotNull($resultado, 'Se debe obtener un usuario activo.');
        $this->assertEquals('Usuario 1', $resultado['nombre']);  // Cambié [0] por [] para que coincida con la estructura.
    }
    
    public function testObtenerUnoConIdInactivo(){
        // Insertamos un usuario inactivo
        $this->usuarios->agregarUsuario('Usuario 2', 'user2', '5678', 'USER','prueba@gmail.com');
        $inactivoID = $this->usuarios->getDbConnection()->lastInsertId();
        
        // Marcamos el usuario como inactivo
        $this->usuarios->getDbConnection()->exec("UPDATE usuarios SET activo = 0 WHERE id = $inactivoID");
    
        // Intentamos obtener el usuario inactivo
        $resultado = $this->usuarios->obtenerUno($inactivoID);
    
        // Verificamos que no se obtenga el usuario
        $this->assertFalse($resultado, 'No se debe obtener un usuario inactivo.');
    }
    public function testObtenerUnoConIdNoExistente(){
   
        $resultado = $this->usuarios->obtenerUno(99999);   

        // Verificamos que no se retorne nada
        $this->assertFalse($resultado, 'No se debe obtener un proveedor inactivo.');

    }

    public function testagregarUsuario(){
        $this->usuarios->agregarUsuario('Usuario 1', 'user1', '1234', 'ADMIN','prueba@gmail.com');
        $proveedorId = $this->usuarios->getDbConnection()->lastInsertId();   
        
        $resultado=$this->usuarios->obtenerUno($proveedorId);

        $this->assertNotNull($resultado, 'Se debe obtener un proveedor activa.');
        $this->assertEquals('Usuario 1', $resultado['nombre']);        
    }

    public function testagregarUsuarionull(){

            //Insertamos un usuario con valores null
            $resultado = $this->usuarios->agregarUsuario('', '', '1234', 'ADMIN','prueba@gmail.com');
    
            $this->assertFalse($resultado, 'El USUARIO no debe ser agregado con datos faltantes.');    
        
    }

    public function testeditarUsuario(){
        //Insertamos un usuario
        $this->usuarios->agregarUsuario('Usuario 1','user1','1234','ADMIN','prueba1@gmail.com');
        $ID = $this->usuarios->getDbConnection()->lastInsertId();

        $this->usuarios->editarUsuario($ID,'Usuario Editado','','EMPLEADO');
        
        $resultado = $this->usuarios->obtenerUno($ID);

        $this->assertEquals('Usuario Editado', $resultado['nombre']);   
        $this->assertEquals('EMPLEADO', $resultado['tipo_usuario']); 
    }

    public function testeditarUsuarioInexistente(){

        $resultado = $this->usuarios->editarUsuario(99999 , 'Usuario Editado','','ADMIN','prueba@gmail.com');        ;

        $this->assertFalse($resultado, 'No se debe obtener un usuario inexistente.');  
    }

    public function testeliminarUsuario(){
        //Insertamos 2 usuarios
        $this->usuarios->agregarUsuario('Usuario 1','user1','1234','ADMIN','prueba1@gmail.com');
        $ID1 = $this->usuarios->getDbConnection()->lastInsertId();

        $this->usuarios->agregarUsuario('Usuario 2','user2','1234','EMPLEADO','prueba2@gmail.com');
        $ID2 = $this->usuarios->getDbConnection()->lastInsertId();

        //Eliminamos el usuario 2
        $this->usuarios->eliminarUsuario($ID2);

        $resultados = $this->usuarios->obtenerTodos();

        // Verificamos que se obtienen los nombres de los usuarios correctamente
        $this->assertCount(1, $resultados, 'Se deben obtener un proveedor activo.');
        $this->assertEquals('Usuario 1', $resultados[0]['nombre']);         
    }

    public function testeliminarUsuarioInexistente(){

        $resultado = $this->usuarios->eliminarUsuario(99999);

        $this->assertFalse($resultado, 'No se debe obtener un proveedor que no existe.');

    }
    protected function tearDown(): void
    {
        // Limpia la tabla de bienes después de cada prueba
        $this->usuarios->getDbConnection()->exec("DELETE FROM bienes");
        $this->usuarios->getDbConnection()->exec("DELETE FROM entradas_bienes");
        $this->usuarios->getDbConnection()->exec("DELETE FROM usuarios");
    }    
}