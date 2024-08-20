<?php 
      //clase para conectarse con la base de datos postgreSQL
class DataBase {
          private $host;//direccion ip del host donde nos conectamos a la bd
          private $bd;//nombre de la base de datos
          private $usuario;//usuario de conexion
          private $pass;//clave del usuario de conexion
          private $port;
		  private $link;//almacenamos el link para luego destruirlo
		  private $stmt; 
		  private $cnn;                //emsamblamos el string de conexion
          //constructor en el constructor colocamos los datos por defecto, a fin de recibir de manera opcional

 
     function __construct($host='localhost', $bd='ib2_oesvica', $user='root', $pass='', $port='3306'){
      //   function __construct($host='localhost', $bd='ib_demo', $user='ip_pedido', $pass='$$334x6rZaGXNree', $port='5432'){
       
		       //asigno valores para ensamblar el string de conexion
       $this->host=$host;
       $this->bd=$bd;
       $this->usuario=$user;
       $this->pass=$pass;
       $this->port=$port;	
     } 
          //funcion que ejecuta la consulta en la base de datos 
          //en esta funcion envio el sql puede ser insert, update, select
     public function conexion(){
       
			/*
				$cnn = mysql_connect($host, $usuario,$conf_pass) or die 
	       ('NO SE PUDO CONECTAR CON LA BASE DE DATOS MYSQL:'. mysql_error());	
	mysql_select_db($bd_cnn,$cnn);	
	    	     $datos_bd=" dbname='$this->bd' user='$this->usuario' host='$this->host' password='$this->pass' 
			                 port='$this->port' ";
			*/
		/*
			$cnn = mysql_connect($host, $usuario, $password) or die 
	       ('NO SE PUDO CONECTAR CON LA BASE DE DATOS MYSQL:'. mysql_error());	
        mysql_select_db($bd_cnn,$cnn);	*/
        
//	    	   $datos_bd="$this->host, $this->usuario, $this->pass";
               //establecemos el link
        $link = mysql_connect($this->host, $this->usuario, $this->pass);
        mysql_select_db($this->bd, $link);	
        mysql_query("SET NAMES 'utf8'");
               //emsamblamos el string de conexion 
               //cargamos la variable para el destructor el cual elimina la conexion				
        if($link == false){
          throw new Exception( " Error MySql Conexion ");
        }
        
        $this->link = $link;	
        return $this->link;
      }

      public function consultar($sql)
      {   
			 //ejecutamos la consulta 
        $query = mysql_query($sql, $this->conexion());
            //  if(!$query) echo $sql;//si no ejecuta la consulta imprimo el sql que llega solo cuando hacemos pruebas
        return $query;
      }

      /*Método para obtener una fila de resultados de la sentencia sql*/
      public function obtener_fila($stmt,$fila){

        if ($fila==0){
          $this->array=mysql_fetch_array($stmt);
        }else{
          $this->array=mysql_fetch_array($stmt,$fila);
        }
        return $this->array;
      }



      public function fetch_assoc($stmt){
			 //ejecutamos la consulta 
        $this->array =mysql_fetch_assoc($stmt);
            //  if(!$query) echo $sql;//si no ejecuta la consulta imprimo el sql que llega solo cuando hacemos pruebas
        return $this->array;
      }


      /*Método para obtener una fila de resultados de la sentencia sql*/
      public function num_fila($stmt){
        
        $this->stmt=mysql_num_rows($stmt);
        
        return $this->stmt;
      }

         //destructor: aca elimino la conexion con postgres
      function __destruct()
      {
        mysql_close($this->link);
      }		  
    }
    ?>
