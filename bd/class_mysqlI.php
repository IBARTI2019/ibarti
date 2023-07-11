<?php
error_reporting(0);
if(!defined("SPECIALCONSTANT")) die ("Acceso denegado");

class DataBase {
//  private $link, $host, $user, $pass, $database,  $charset;

  private $host = host;
  private $user = user;
  private $pass = pass;
  private $database = database;
  private $charset = db_charset;
  public $con;
  public function __construct(){
        // /$db_cfg = requiere_once "config/database.php";
    $conn = new MySQLI($this->host, $this->user, $this->pass, $this->database);
    $timeout = 30;  /* thirty seconds for timeout */
    $conn = mysqli_init();
    $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, $timeout ) ||
    die( 'mysqli_options croaked: ' . $conn->error );
    $conn->real_connect($this->host, $this->user, $this->pass, $this->database) ||
    die( 'mysqli_real_connect croaked: ' . $conn->error );

    if(mysqli_connect_errno()){
            //  throw new Exception("Error al conectarse con el servidor MySQL: ".$this->mysqli->error);
      $result = array();

      $result['error'] = true;
      $result['mensaje'] ="Error al conectarse con el servidor MySQL.  DB.: ".$this->database."";

      print_r(json_encode($result));
      return json_encode($result);
      exit();
    }
    $conn->set_charset($this->charset);
            //  $this->set_charset($this->charset);
    return  $this->con = $conn;
      // return $this->con;
  }

  public function consultar($sql){
    $result =  $this->con->query($sql);

    if ($result === false) {
      throw new Exception($result = $this->error());
    }
    return $result;
  }

  public function  num_fila($result){
    return $result->fetch_array(MYSQLI_NUM);
  }

  public function obtener_fila($result){
    return $result->fetch_array(MYSQLI_BOTH);
  }

  public function obtener_num($result){
    return $result->fetch_array(MYSQLI_NUM);
  }

  public function obtener_name($result){
    return $result->fetch_array(MYSQLI_ASSOC);
  }

  public function liberar(){
    $this->con->close();
  }

  public function getErrorNo(){
    return mysqli_errno($this->con);
  }
  public function getError(){
    return mysqli_error($this->con);
  }

  public function isConnected(){
    return !is_null($this->con);
  }
  public function escape($var){
    return mysqli_real_escape_string($this->con, $var);
  }

  public function result($result){
    print_r(json_encode($result));
    return json_encode($result);;
  }


  public function start(){

    return    $this->consultar("START TRANSACTION");
  }
  public function commit(){

   return  $this->consultar("COMMIT");
 }

 public function rollback(){

   return  $this->consultar("ROLLBACK");

 }

 public function error(){
  $num   = mysqli_errno($this->con);
  $err_m = mysqli_error($this->con);

  $this->error = "Numero de error: $num;  Error: $err_m ";
  $error = array(
                  //  array(1305," Procedimiento No Existe "),
    array(1062," Clave Primaria Duplicada "),
    array(1451," No se Puede Borrar Este Registro, Ya que tiene registros Relacionados (FK) "),
    array(4," cuatro ")
  );

  foreach( $error as $key =>$value) {
    $valor = $error;
    if($valor[$key][0] == $num){
      $this->error  = "Numero de error: $num;  Error: ".$valor[$key][1]."";
    }
  }
  return $this->error;
}

public function log_error($apl, $archivo, $us, $error, $exe){

  $error = str_replace('\'', '"', $error);
  $exe = str_replace('\'', '"', $exe);
  $sql = "INSERT INTO log  (fecha, archivo, origen, usuario, equipo, ip,  error, ejecutando)
  VALUES (CURRENT_TIMESTAMP, '$archivo', '$apl', '$us', 'LOCAL', '1111.', '$error', '$exe')" ;
  $this->con->query($sql);
}
}
?>
