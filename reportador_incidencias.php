<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reportador_incidencias extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->sesion_model->ValidarSesion();
		$this->rol_sistema = array("administrador_casos", "agente_incidencia", "reportador_incidencias");
		$this->id_usuario = $this->session->userdata("sava_idusuario");
		$this->sesion_model->ValidarRolUsuario($this->id_usuario, $this->rol_sistema);
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->helper('file');
		$this->load->library('grocery_CRUD');
		$this->load->model("m_reportador_incidencias"); //cargamos modelo para todas las funciones
		$this->load->model("m_mensajes_incidencias");
		$this->load->model("m_gestion_incidencias");
		$this->perPage = 10;

		$this->centro = $this->session->userdata("sava_centro");
	}

	function index()
	{
		redirect("reportador_incidencias/preguntas_frecuentes");
	}

	function radicar()
	{
		$data['titulo'] = "<i class='fa fa-commenting'></i> Reportar Incidencias LMS | Reportar Caso";
		//si es instructor mostramos formulario diferente.
		if ($this->db->query("SELECT * FROM usuario_rolinstitucional WHERE usuario_usr_id=$this->id_usuario and rolinstitucional_roi_id=3")->row_array()) {
			redirect("reportador_incidencias/radicar_herramienta");
			exit();
		} else {
			$data['contenido'] = $this->load->view("reportador_incidencias/v_formulario_radicacion", "", TRUE);
		}

		$this->load->view('v_principal', $data);
	}

	function radicar_herramienta()
	{
		$data['titulo'] = "<i class='fa fa-commenting'></i> Reportar Incidencias LMS | Reportar Caso";
		//si es instructor mostramos formulario diferente.
		if ($this->db->query("SELECT * FROM usuario_rolinstitucional WHERE usuario_usr_id=$this->id_usuario and rolinstitucional_roi_id=3")->row_array()) {
			$data['contenido'] = $this->load->view("reportador_incidencias/v_formulario_radicacion_instructor", "", TRUE);
		} else {
			$data['contenido'] = $this->load->view("reportador_incidencias/v_formulario_radicacion", "", TRUE);
		}

		$this->load->view('v_principal', $data);
	}

	function mis_casos_usuario()
	{
		$this->load->library('pagination');

		$data = array();

		if (!isset($_POST['fecha_inicial'])) {
			$f_init = date("2020-01-01");
			$f_hoy  = date('Y-m-d');
		} else {
			$f_init = $_POST['fecha_inicial'];
			$f_hoy  = $_POST['fecha_final'];
		}
		if (isset($_POST['estado_caso'])) {
			$estado_caso = $_POST["estado_caso"];
		} else {
			$estado_caso = "abiertos";
		}

		//get rows count
		$totalRec = number_format($this->m_gestion_incidencias->VerTotalCasosUsuario($f_init, $f_hoy, $this->id_usuario));

		//pagination config
		$config['base_url']    = base_url() . 'reportador_incidencias/mis_casos_usuario';
		$config['uri_segment'] = 3;
		$config['total_rows']  = $totalRec;
		$config['per_page']    = $this->perPage;

		//styling
		$config['num_tag_open']    = '<li>';
		$config['num_tag_close']   = '</li>';
		$config['cur_tag_open']    = '<li class="active"><a href="javascript:void(0);">';
		$config['cur_tag_close']   = '</a></li>';
		$config['next_link']       = '>';
		$config['prev_link']       = '<';
		$config['next_tag_open']   = '<li class="pg-next">';
		$config['next_tag_close']  = '</li>';
		$config['prev_tag_open']   = '<li class="pg-prev">';
		$config['prev_tag_close']  = '</li>';
		$config['first_link']      = '<<';
		$config['first_tag_open']  = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_link']       = '>>';
		$config['last_tag_open']   = '<li>';
		$config['last_tag_close']  = '</li>';

		//initialize pagination library
		$this->pagination->initialize($config);

		//define offset
		$page   = $this->uri->segment(3);
		$offset = !$page ? 0 : $page;

		//get rows
		$datos["fecha_inicio"] = $f_init;
		$datos["fecha_final"]  = $f_hoy;
		$datos["total_regs"]   = $totalRec;
		$datos["encuestas"]    = $this->m_gestion_incidencias->VerResumenCasosUsuarioXEncuesta($f_init, $f_hoy, $this->id_usuario);
		$datos["casos"]        = $this->m_reportador_incidencias->VerResumenCasosUsuario($f_init, $f_hoy, $this->id_usuario, $estado_caso);
		//$this->output->enable_profiler(TRUE);
		$data['titulo']    = '<i class="fa fa-commenting"></i> Reportador Incidencias LMS | Listado';
		$datos["estado_seleccionado"] = $estado_caso;
		$data['contenido'] = $this->load->view("reportador_incidencias/v_casos_usuario", $datos, TRUE);
		$data["otroencabezado"] =	'<link rel="stylesheet" href="' . base_url() . 'plantilla/dist/css/estilos_tipocasos.css">';

		$this->load->view('v_principal', $data);
	}

	function preguntas_frecuentes()
	{
		$data['titulo'] = "<i class='fa fa-commenting'></i> Reportar Incidencias LMS | Preguntas Frecuentes";
		$data['contenido'] = $this->load->view("reportador_incidencias/v_preguntas_frecuentes", "", TRUE);
		//$data['contenido']="".$contenido_antes." ".$data['contenido']."";

		$this->load->view('v_principal', $data);
	}

	function preguntas_frecuentes_articulo()
	{
		$data['titulo'] = "<i class='fa fa-commenting'></i> Reportar Incidencias LMS | Articulo Preguntas Frecuentes";
		$data['contenido'] = $this->load->view("reportador_incidencias/v_preguntas_frecuentes_articulo", "", TRUE);
		//$data['contenido']="".$contenido_antes." ".$data['contenido']."";
		$this->load->view('v_principal', $data);
	}

	public function _salida($output = null, $titulo = null, $contenido_pre = null)
	{
		$otroencabezado = '';
		foreach ($output->css_files as $file) {
			$otroencabezado .= '  <link type="text/css" rel="stylesheet" href="' . $file . '" />';
		}
		foreach ($output->js_files as $file) {
			$otroencabezado .= '<script src="' . $file . '"></script>';
		}
		$data['titulo'] = $titulo;
		$data['contenido'] = $this->load->view("v_salida", $output, TRUE);
		//$data['contenido']="".$contenido_antes." ".$data['contenido']."";
		$data['otroencabezado'] = $otroencabezado;
		$this->load->view('v_principal', $data);
	}

	function test_email()
	{
		$this->load->model("m_mensajes");
		$dato = array("email" => "manuel.celemin@misena.edu.co", "asunto" => "Test", "mensaje" => "Mi mensaje");
		$this->m_mensajes->enviar_email($dato);
	}

	function datos_categoria()
	{
		$id = $_POST["id"];
		//echo $id;exit();
		$sql2 = "SELECT * FROM `lms_tipo_caso` LEFT JOIN lms_herramientaslms ON lms_herramientaslms.her_id=lms_tipo_caso.lms_herramientaslms_her_id 
		WHERE tca_estado=1 and categoria_tipo_caso_ctc_id='" . $id . "' order by her_nombre,tca_nombre asc";
		$t_result2 = $this->db->query($sql2)->result_array();
		$listatipocaso = "<select class='form-control' required name='tipo_caso' id='tipo_caso'><option></option>";
		foreach ($t_result2 as $t_row2) {
			$listatipocaso .= "<option value='" . $t_row2['tca_id'] . "'>" . $t_row2['her_nombre'] . " - " . $t_row2['tca_nombre'] . "</option>";
		}
		$listatipocaso .= "</select>";
		echo $listatipocaso;
	}

	function datos_herramientas()
	{
		$id = $_POST["id"];
		//echo $id;exit();
		$sql2 = "SELECT lms_herramientaslms.*,lms_tipo_caso.* FROM lms_herramientaslms LEFT JOIN 
        lms_tipo_caso ON lms_tipo_caso.lms_herramientaslms_her_id=lms_herramientaslms.her_id 
        LEFT JOIN lms_categoria_tipo_caso on lms_categoria_tipo_caso.ctc_id=lms_tipo_caso.categoria_tipo_caso_ctc_id 
        LEFT JOIN lms_categoria_tipo_caso_rolinstitucional
        ON lms_categoria_tipo_caso_rolinstitucional.lms_categoria_tipo_caso_ctc_id=lms_categoria_tipo_caso.ctc_id
         LEFT JOIN usuario_rolinstitucional ON usuario_rolinstitucional.rolinstitucional_roi_id=lms_categoria_tipo_caso_rolinstitucional.lms_rolinstitucional_roi_id 
         WHERE lms_herramientaslms_her_id='" . $id . "' and tca_estado='DISPONIBLE' AND usuario_usr_id='" . $this->id_usuario . "' GROUP BY tca_id order by tca_nombre asc";

		$t_result2 = $this->db->query($sql2)->result_array();
		$listatipocaso = "<select class='form-control' required name='tipo_caso' id='tipo_caso'><option></option>";
		foreach ($t_result2 as $t_row2) {
			$listatipocaso .= "<option value='" . $t_row2['tca_id'] . "'>" . $t_row2['her_nombre'] . " - " . $t_row2['tca_nombre'] . "</option>";
		}
		$listatipocaso .= "</select>";
		echo $listatipocaso;
	}

	function mis_casos()
	{
		//Hay que validar segun el tipo de usuario {Usuario / Agente} para llamar la vista		
		$data['contenido'] = $this->load->view("reportador_incidencias/v_mis_casos", "", TRUE);
		$this->load->view('v_principal', $data);
	}

	function ver_caso2()
	{
		$data['contenido'] = $this->load->view("reportador_incidencias/v_ver_caso", "", TRUE);
		$this->load->view('v_principal', $data);
	}

	function ver_caso()
	{
		//$caso=array("id"=>1,"nombre_instructor"=>"Manuel");
		//echo "aca";exit();
		$id = $this->uri->segment(3); // Se obtiene el numero del caso


		if ($datoscaso = $this->db->where("sop_id", $id)->get("lms_caso")->row_array()) {
			//si el rol es de reportador, pero no fue el que lo reporto no dejamos crear nota;    
			if ($this->session->userdata("sava_rolactual") == "reportador_incidencias"  && $datoscaso["usuario_usr_id"] != $this->id_usuario) {
				redirect("reportador_incidencias");
				exit();
			}
		} else {
			redirect("reportador_incidencias");
			exit();
		}

		$caso = $this->m_reportador_incidencias->DatosCaso($id);
		//print_r($caso);

		//Quite esta restriccion, evaluar funcionamiento

		/*if ($caso["usuario_usr_id"]!=$this->id_usuario){
				redirect("reportador_incidencias/mis_casos");exit();
		}*/
		//$this->output->enable_profiler(TRUE);

		// print_r($caso);exit();
		$data['titulo']    = '<i class="fa fa-tags"></i> Incidencias LMS | Caso Asignado ' . $id;   //SAVA-182: Ajuste de textos
		$data['contenido'] = $this->load->view("reportador_incidencias/v_ver_caso", $caso, TRUE);

		$this->load->view('v_principal', $data);
	}

	function vista_impresion()
	{
		//$caso=array("id"=>1,"nombre_instructor"=>"Manuel");
		//echo "aca";exit();
		$id = $this->uri->segment(3); // Se obtiene el numero del caso


		if ($datoscaso = $this->db->where("sop_id", $id)->get("lms_caso")->row_array()) {
			//si el rol es de reportador, pero no fue el que lo reporto no dejamos crear nota;    
			if ($this->session->userdata("sava_rolactual") == "reportador_incidencias"  && $datoscaso["usuario_usr_id"] != $this->id_usuario) {
				redirect("reportador_incidencias");
				exit();
			}
		} else {
			redirect("reportador_incidencias");
			exit();
		}

		$caso = $this->m_reportador_incidencias->DatosCaso($id);
		//print_r($caso);

		//Quite esta restriccion, evaluar funcionamiento

		/*if ($caso["usuario_usr_id"]!=$this->id_usuario){
				redirect("reportador_incidencias/mis_casos");exit();
		}*/
		//$this->output->enable_profiler(TRUE);

		// print_r($caso);exit();

		$this->load->view("reportador_incidencias/v_impresion", $caso);

		//$this->load->view('v_principal', $data);    
	}

	function prueba()
	{
		//$this->output->enable_profiler(TRUE);
		$datoaenviar = $this->m_reportador_incidencias->VerDatosUsuario($this->id_usuario);
		$mensaje = array("asunto" => "saludo", "mensaje" => "hola, esto es una preuba", "email" => $datoaenviar["email"]);
		$this->m_mensajes->enviar_email($mensaje);
		$variable2 = $this->load->view("reportador_incidencias/v_prueba", $datoaenviar, TRUE);
		$data['contenido'] = $variable2;
		$this->load->view('v_principal', $data);
	}

	public function reportar_action()
	{
		$this->load->helper(array('form'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules(
			'fichas',
			'Fichas',
			'required'
		);
		$this->form_validation->set_rules(
			'numero_identificacion',
			'Número identificación de quien se le presenta el inconveniente',
			'required'
		);
		$this->form_validation->set_rules(
			'fecha',
			'Fecha desde cuando se le presenta el inconveniente',
			'required'
		);

		$this->form_validation->set_rules(
			'descripcion',
			'Descripcion',
			'required'
		);

		if ($_POST['link_evidencias'] == "") {
			$this->form_validation->set_rules('files', '', 'callback_file_check_required');
		} else {
			$this->form_validation->set_rules('files', '', 'callback_file_check');
		}

		if ($this->form_validation->run() == FALSE) {
			// Retornar errores de validacion
			echo "<div class='alert alert-danger'>" . validation_errors() . "</div>";
		} else {

			$newdata = array(
				"lms_tipo_caso_tca_id" => $_POST["tipo_caso"],
				"sop_fichas" => $_POST["fichas"],
				"tipodocumento_doc_id" => $_POST["tipo_documento"],
				"sop_num_identificacion" => $_POST["numero_identificacion"],
				"sop_fecha_problema" => $_POST["fecha"],
				"sop_descripcion" => $_POST["descripcion"],
				"lms_estado_caso_eca_id" => 0,
				"lms_tipo_usuario_tus_id" => $_POST["tipo_usuario"],
				"lms_estado_caso_eca_id" => 0,
				"lms_ambiente_amb_id" => $_POST["ambiente"],
				"sop_link" => $_POST["link_evidencias"],
				"usuario_usr_id" => $this->id_usuario,
				"centro_cen_id" => $this->centro
			);
			//INICIO DE ASIGNACION DE AGENTE
			//antes de insertar buscamos el ultimo agente que atendió un caso similar.

			$arrayagentesasignar = array(); //arreglo usado para listar agentes disponibles para asignación al caso.
			$agente_asignado = array("usr_id" => 0);
			$TEXTO_DEBUG_ASIGNACION = "";
			$agentes_disponibles_tipo_caso = $this->db->query("SELECT usr_id,usr_email_institucional FROM lms_tipo_caso_agente left join usuario_rolinstitucional on usuario_rolinstitucional.uro_id=lms_tipo_caso_agente.usuario_rolinstitucional_uro_id 
				LEFT JOIN usuario on usuario_rolinstitucional.usuario_usr_id = usuario.usr_id 
				WHERE lms_tipo_caso_tca_id='" . $_POST["tipo_caso"] . "' AND estadousuario_eus_id=1   ");

			$bandera_asignar = false;
			if ($agentes_disponibles_tipo_caso->num_rows() > 0) {
				//exite restriccion de asignación, buscamos el siguiente disponible dentro de la restricción
				$arreglo_de_disponibles = $agentes_disponibles_tipo_caso->result_array();
				//eliminamos los no disponibles por limites				   

				//fin restriccion 
				if ($row_ultimo_agente_por_tipo_de_caso = $this->db->query("SELECT sop_agente_inicial_usr_id FROM lms_caso  WHERE lms_tipo_caso_tca_id='" . $_POST["tipo_caso"] . "' ORDER BY sop_id DESC LIMIT 1")->row_array()) {

					$ultimo_agente_por_tipo_de_caso = $row_ultimo_agente_por_tipo_de_caso["sop_agente_inicial_usr_id"];
				} else {
					$ultimo_agente_por_tipo_de_caso = 0;
				}

				$arreglo_de_disponibles2 = $agentes_disponibles_tipo_caso->result_array();

				$arreglo_de_disponibles3 = $agentes_disponibles_tipo_caso->result_array();
				$arrayproximo1 = array();
				foreach ($arreglo_de_disponibles3 as $clave => $ag3) {
					$arreglo_de_disponibles2[] = $ag3;
					$arrayproximo1[] = $ag3;
					$TEXTO_DEBUG_ASIGNACION .= "<br/>Agentes con rol disponible: (" . $ag3["usr_id"] . ") " . $ag3["usr_email_institucional"] . "";
				}

				$primero = array();
				$ultimo = array();
				$arrayproximo = array();
				$disponible = false;
				//print_r($arrayproximo1);
				$arrayproximo1 = array_reverse($arrayproximo1);
				foreach ($arrayproximo1 as $pp) {
					$disponible = $this->m_reportador_incidencias->ValidarDisponibilidadLimites($pp["usr_id"]);
					if ($disponible == false) {
						$TEXTO_DEBUG_ASIGNACION .= "<br/>AGENTE " . $pp["usr_email_institucional"] . " bloqueado por admin";
					}
					if (count($primero) == 0) {
						if ($disponible == true) {
							$primero = $pp;
						}
					}
					$arrayproximo[$pp["usr_id"]] = $ultimo;
					if ($disponible == true) {
						$ultimo = $pp;
					}
				}
				if (count($primero) > 0) {
					$arrayproximo[$primero["usr_id"]] = $ultimo;
				}
				$ultimopp = array();

				/*
				foreach($arrayproximo as $cv=> $ppp){
					if(isset($ppp["usr_id"])){
						$ultimopp=$ppp;
					}	else {
						$arrayproximo[$cv]=$ultimopp;
					}
				}
				*/
				//print_r($arrayproximo);
				//$TEXTO_DEBUG_ASIGNACION.="<br/>-----------ARRAY ASIGNACION----------<br/>";
				//print_r($arreglo_de_disponibles2);exit();
				//eliminamos los no disponibles por limites
				/*foreach($arrayproximo AS $V=> $pp2 ){
					
					//$TEXTO_DEBUG_ASIGNACION.="<br/>".$V."=>".$pp2["usr_id"]."";
				}*/

				if (isset($arrayproximo[$ultimo_agente_por_tipo_de_caso])) {
					$agente_asignado = $arrayproximo[$ultimo_agente_por_tipo_de_caso];
				}
				$TEXTO_DEBUG_ASIGNACION .= "<br/>---------------------<br/>";

				//$this->m_reportador_incidencias->ValidarDisponibilidadLimites($ag["usr_id"])==false			   

				//si llegamos al ultimo agente y ese fue el que se asigno por ultima vez asignamos al primero disponible
				if ($agente_asignado["usr_id"] == 0) {
					foreach ($arrayproximo as $vvv) {
						$agente_asignado = $vvv;
					}
				}
			} else {
				//no hay restricción de asignación, buscamos en todo el listado de agentes.
				if ($row_ultimo_agente_por_tipo_de_caso = $this->db->query("SELECT sop_id,sop_agente_inicial_usr_id FROM lms_caso 
				    LEFT JOIN lms_tipo_caso ON  
				    lms_tipo_caso.tca_id=lms_caso.lms_tipo_caso_tca_id WHERE tca_todos_soporte='SI' ORDER BY sop_id DESC LIMIT 1")->row_array()) {
					$ultimo_agente_por_tipo_de_caso = $row_ultimo_agente_por_tipo_de_caso["sop_agente_inicial_usr_id"];
				} else {
					$ultimo_agente_por_tipo_de_caso = 0;
				}
				$TEXTO_DEBUG_ASIGNACION .= "<br/>ID ULTIMO AGENTE caso " . $row_ultimo_agente_por_tipo_de_caso["sop_id"] . ": " . $ultimo_agente_por_tipo_de_caso . "";

				$codigorolagente = "agente_incidencia";
				$this->db->select("usr_id,usr_email_institucional");
				$this->db->from("usuario_rolinstitucional");
				$this->db->join('usuario', 'usuario.usr_id = usuario_rolinstitucional.usuario_usr_id');
				$this->db->join('rolinstitucional', 'rolinstitucional.roi_id = usuario_rolinstitucional.rolinstitucional_roi_id');
				$this->db->join('rolinstitucional_rolsistema', 'rolinstitucional.roi_id = rolinstitucional_rolsistema.rolinstitucional_roi_id');
				$this->db->join('rolsistema', 'rolsistema.ros_id = rolinstitucional_rolsistema.rolsistema_ros_id');
				$this->db->where('rolsistema.ros_codigo', $codigorolagente);
				$this->db->where('estadousuario_eus_id', 1);
				$this->db->group_by("usr_id");
				$this->db->order_by('usr_id', 'ASC');

				$agentes_disponibles_todos = $this->db->get();
				//	 print_r($agentes_disponibles_todos)  ;  	

				//exite restriccion de asignación, buscamos el siguiente disponible dentro de la restricción
				$arreglo_de_disponibles2 = $agentes_disponibles_todos->result_array();

				$arreglo_de_disponibles3 = $agentes_disponibles_todos->result_array();
				$arrayproximo1 = array();
				foreach ($arreglo_de_disponibles3 as $clave => $ag3) {
					$arreglo_de_disponibles2[] = $ag3;
					$arrayproximo1[] = $ag3;
					$TEXTO_DEBUG_ASIGNACION .= "<br/>Agentes con rol disponible: (" . $ag3["usr_id"] . ") " . $ag3["usr_email_institucional"] . "";
				}

				$primero = array();
				$ultimo = array();
				$arrayproximo = array();
				$disponible = false;
				//print_r($arrayproximo1);
				$arrayproximo1 = array_reverse($arrayproximo1);
				foreach ($arrayproximo1 as $pp) {
					$disponible = $this->m_reportador_incidencias->ValidarDisponibilidadLimites($pp["usr_id"]);
					if ($disponible == false) {
						$TEXTO_DEBUG_ASIGNACION .= "<br/>AGENTE " . $pp["usr_email_institucional"] . " bloqueado por admin";
					}
					if (count($primero) == 0) {
						if ($disponible == true) {
							$primero = $pp;
						}
					}
					$arrayproximo[$pp["usr_id"]] = $ultimo;
					if ($disponible == true) {
						$ultimo = $pp;
					}
				}
				if (count($primero) > 0) {
					$arrayproximo[$primero["usr_id"]] = $ultimo;
				}
				$ultimopp = array();

				/*
					foreach($arrayproximo as $cv=> $ppp){
						if(isset($ppp["usr_id"])){
							$ultimopp=$ppp;
						}	else {
							$arrayproximo[$cv]=$ultimopp;
						}
					}
					*/
				//print_r($arrayproximo);
				//$TEXTO_DEBUG_ASIGNACION.="<br/>-----------ARRAY ASIGNACION----------<br/>";
				//print_r($arreglo_de_disponibles2);exit();
				//eliminamos los no disponibles por limites
				/*foreach($arrayproximo AS $V=> $pp2 ){
						
						//$TEXTO_DEBUG_ASIGNACION.="<br/>".$V."=>".$pp2["usr_id"]."";
					}*/

				if (isset($arrayproximo[$ultimo_agente_por_tipo_de_caso])) {
					$agente_asignado = $arrayproximo[$ultimo_agente_por_tipo_de_caso];
				}
				$TEXTO_DEBUG_ASIGNACION .= "<br/>---------------------<br/>";

				//$this->m_reportador_incidencias->ValidarDisponibilidadLimites($ag["usr_id"])==false

				//si llegamos al ultimo agente y ese fue el que se asigno por ultima vez asignamos al primero disponible
				if ($agente_asignado["usr_id"] == 0) {
					foreach ($arrayproximo as $vvv) {
						$agente_asignado = $vvv;
					}
				}
			}
			if (!isset($agente_asignado["usr_id"])) {
				$agente_asignado["usr_id"] = 0;
			}
			$newdata["sop_agente_inicial_usr_id"] = $agente_asignado["usr_id"];
			$newdata["sop_agente_atendiendo_usr_id"] = $agente_asignado["usr_id"];
			$TEXTO_DEBUG_ASIGNACION .= "<BR/>AGENTE ASIGNADO " . $agente_asignado["usr_id"] . " ";
			$newdata["sop_debug_asignacion"] = $TEXTO_DEBUG_ASIGNACION;
			//echo $TEXTO_DEBUG_ASIGNACION;
			//FIN DE ASIGNACION DE AGENTE
			if ($this->db->insert("lms_caso", $newdata)) {
				$idcaso = $this->db->insert_id();

				//asignamos agente de soporte técnico
				//1. Buscamos si hay agentes

				//crear carpeta para archivos del caso
				$rut = 'archivos/reportador_incidencias/' . $idcaso . '';
				// echo $rut;exit();
				mkdir($rut);
				$uploadPath = $rut;
				write_file("$rut/index.html", "", 'w+');

				$filesCount = count($_FILES['files']['name']);
				for ($i = 0; $i < $filesCount; $i++) {
					$_FILES['file']['name']     = $_FILES['files']['name'][$i];
					$_FILES['file']['type']     = $_FILES['files']['type'][$i];
					$_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
					$_FILES['file']['error']     = $_FILES['files']['error'][$i];
					$_FILES['file']['size']     = $_FILES['files']['size'][$i];

					// File upload configuration
					//$uploadPath = 'archivos/reportador_incidencias/';
					$config['upload_path'] = $uploadPath;
					$config["file_name"] = url_title($_FILES['files']['name'][$i]);
					$config['allowed_types'] = 'gif|jpg|jpeg|png|pdf|doc|docx|xls|xlsx|bmp|ppt|pptx|csv';
					$this->load->library('upload', $config);
					$this->upload->initialize($config);

					// Upload file to server
					if ($this->upload->do_upload('file')) {
						// Uploaded file data
						$fileData = $this->upload->data();
						$uploadData[$i]['cev_archivo'] = $fileData['file_name'];
						$uploadData[$i]['lms_caso_sop_id'] = $idcaso;
					} else {
						//  echo $data['error_msg'] = $this->upload->display_errors();exit();
					}
				}
				if (!empty($uploadData)) {
					//print_r($uploadData);
					// Insert files data into the database
					$this->db->insert_batch("lms_caso_evidencias", $uploadData);
				}
				$this->m_mensajes_incidencias->MensajeCasoCreado($idcaso);

				//Inicio bloque SAVA-84: notificar alerta a los administrador_casos
				$IDtipoCaso = $newdata["lms_tipo_caso_tca_id"];	//obtenemos el id_tipo_caso

				$this->db->select("tca_alerta, tca_nombre, her_nombre");						//SELECT tca_alerta, tca_nombre, herr_nombre
				$this->db->from("lms_tipo_caso");												//FROM lms_tipo_caso
				$this->db->join("lms_herramientaslms", "lms_herramientaslms_her_id = her_id");	//JOIN lms_herramientaslms ON lms_herramientaslms_her_id = her_id
				$this->db->where("tca_id", $IDtipoCaso);										//WHERE tca_id = $IDtipoCaso
				$rta = $this->db->get()->first_row('array');		//obtenemos un array de solo el primer registro que resulte

				if ($rta["tca_alerta"] == 1) {			//si el tca_alerta == 1
					$this->m_mensajes_incidencias->notificar_alerta($idcaso, $rta);		//hace la notificación teniendo en cuenta esos datos
				}
				//Fin bloque SAVA-84

				echo "<p class='alert alert-success'>Registro Completo. </p><script>window.location.replace('" . site_url() . "/reportador_incidencias/ver_caso/" . $idcaso . "');</script>";
			} else {
				echo "<p class='alert alert-danger'>Ha ocurrido un error. Intente de nuevo.</p>";
			}
		}
	}

	public function nota_action()
	{
		// print_r($_POST)   ;exit();
		$idcaso = $_POST["lms_caso_sop_id"];
		$devolucion = $_POST["devolucion"];
		if ($datoscaso = $this->db->where("sop_id", $idcaso)->get("lms_caso")->row_array()) {
			//si el rol es de reportador, pero no fue el que lo reporto no dejamos crear nota;    
			if ($this->session->userdata("sava_rolactual") == "reportador_incidencias"  && $datoscaso["usuario_usr_id"] != $this->id_usuario) {
				redirect("reportador_incidencias");
				exit();
			}
			/*si es agente no puede expirar caso*/
			if ($this->session->userdata("sava_rolactual") == "agente_incidencia"  && $datoscaso["lms_estado_caso_eca_id"] != 8 && $_POST["not_estado_nuevo"] == 8) {
				echo "<div class='alert alert-danger'>Error. Usted no tiene permisos para expirar el caso.'</div>";
				exit();
			}

			/*si es agente no puede cerrar caso*/
			if ($this->session->userdata("sava_rolactual") == "agente_incidencia"  && $datoscaso["lms_estado_caso_eca_id"] != 100 && $_POST["not_estado_nuevo"] == 100) {
				echo "<div class='alert alert-danger'>Error. Usted no tiene permisos para cerrar el caso.'</div>";
				exit();
			}

			if ($datoscaso["lms_estado_caso_eca_id"] != 5 && $datoscaso["sop_ticket_proveedor"] == "" && $_POST["not_estado_nuevo"] == 5) {
				//se quiere escalar el caso sin tener id relacionado
				echo "<div class='alert alert-danger'>Error. Usted no ha colocado un ticket relacionado para poder escalar al proveedor lms.</div>
			<script>alert('No puede escalar sin tener ticket relacionado. La nota se perderá al cerrar esta página, entonces debe copiarla, para despues reutilizarla.')
			</script>";
				exit();
			}
			//   $this->output->enable_profiler(TRUE);
			$this->load->helper(array('form'));
			$this->load->library('form_validation');
			$this->form_validation->set_rules(
				'nota',
				'Nota',
				'required'
			);

			$this->form_validation->set_rules('files', '', 'callback_file_check');
			// print_r($_FILES);exit();
			// Verificar validacion correcta
			if ($this->form_validation->run() == FALSE) {
				// Retornar errores de validacion
				echo "<div class='alert alert-danger'>" . validation_errors() . "</div>";
			} else {

				$newdata = array(
					"not_texto" => $_POST["nota"],
					"usuario_usr_id" => $this->id_usuario,
					"lms_caso_sop_id" => $_POST["lms_caso_sop_id"],
					"not_estado_anterior" => $datoscaso["lms_estado_caso_eca_id"]
				);
				//print_r($newdata);
				// Se realiza un incremento de sop_devolucion cuando el estado anterior era "solucionado"
				if ($newdata["not_estado_anterior"] == 101) {
					if ($devolucion == 1) {
						$sql = "UPDATE lms_caso SET sop_devolucion=sop_devolucion+1 WHERE sop_id='" . $datoscaso["sop_id"] . "' LIMIT 1";
						$this->db->query($sql);
					}
				}

				if ($this->session->userdata("sava_rolactual") == "agente_incidencia" || $this->session->userdata("sava_rolactual") == "administrador_casos") {
					$newdata["not_estado_nuevo"] = $_POST["not_estado_nuevo"];
					if (isset($_POST["not_acceso"])) {
						$newdata["not_acceso"] = $_POST["not_acceso"];
					};
				} else {
					//nota del usuario
					$newdata["not_estado_nuevo"]  = $datoscaso["lms_estado_caso_eca_id"];
					if (true) {
						//si esta en pendiente usuario o solucionado y se crea nota cambiamos a EN PROCESO para nueva revisión por agente
						//echo $newdata["not_estado_anterior"];
						$newdata["not_estado_nuevo"] = 1;
					}
					$newdata["not_acceso"] = "";
				}
				//print_r($newdata);//exit();

				if ($this->db->insert("lms_nota_caso", $newdata)) {
					$idnota = $this->db->insert_id();
					if ($this->session->userdata("sava_rolactual") == "agente_incidencia" || $this->session->userdata("sava_rolactual") == "administrador_casos") {

						/*VERIFICACION DE FECHAS para llenado de fechas de solución*/
						if ($newdata["not_estado_nuevo"] == 100 && $datoscaso["lms_estado_caso_eca_id"] != 100) {
							//estamos cerrando caso
							$sql = "UPDATE lms_caso SET sop_fecha_final_cierre = NOW() where sop_id='" . $datoscaso["sop_id"] . "' LIMIT 1";
							$this->db->query($sql);
						}
						/**/
						/*VERIFICACION DE FECHAS para llenado de fechas de solución*/
						if ($newdata["not_estado_nuevo"] == 101 && $datoscaso["lms_estado_caso_eca_id"] != 101) {
							//estamos solucionando caso
							$sql = "UPDATE lms_caso SET sop_fecha_final_solucion = NOW() where sop_id='" . $datoscaso["sop_id"] . "' LIMIT 1";
							$this->db->query($sql);
						}
						/**/

						$this->db->where("sop_id", $_POST["lms_caso_sop_id"])
							->update("lms_caso", array("lms_estado_caso_eca_id" => $newdata["not_estado_nuevo"]));


						// si estaba en estado diferente a pendiente usuario y nota de agente es pendiente usuario actualizamos fecha de expiracion a 6 días
						if ($datoscaso["lms_estado_caso_eca_id"] != 7 && $_POST["not_estado_nuevo"] == 7) {
							$sql = "UPDATE lms_caso SET sop_fecha_expiracion = DATE_ADD(NOW(), INTERVAL 7 DAY) where sop_id='" . $datoscaso["sop_id"] . "' LIMIT 1";
							//echo $sql;exit();
							$this->db->query($sql);
						}
						if ($datoscaso["lms_estado_caso_eca_id"] != 101 && $_POST["not_estado_nuevo"] == 101) {
							$sql = "UPDATE lms_caso SET sop_fecha_cierre = DATE_ADD(NOW(), INTERVAL 7 DAY) where sop_id='" . $datoscaso["sop_id"] . "' LIMIT 1";
							//echo $sql;exit();
							$this->db->query($sql);
						}
					} else {
						//si es nota de usuario en estado usuario o solucionado actualizamos el estado
						if (true) {
							$this->db->where("sop_id", $_POST["lms_caso_sop_id"])
								->update("lms_caso", array("lms_estado_caso_eca_id" => $newdata["not_estado_nuevo"]));
						}
					}

					//crear carpeta para archivos del caso
					$rut = 'archivos/reportador_incidencias/' . $idcaso . '';
					// echo $rut;exit();

					$uploadPath = $rut;
					write_file("$rut/index.html", "", 'w+');

					$filesCount = count($_FILES['files']['name']);
					for ($i = 0; $i < $filesCount; $i++) {
						$_FILES['file']['name']     = $_FILES['files']['name'][$i];
						$_FILES['file']['type']     = $_FILES['files']['type'][$i];
						$_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
						$_FILES['file']['error']     = $_FILES['files']['error'][$i];
						$_FILES['file']['size']     = $_FILES['files']['size'][$i];

						// File upload configuration
						//$uploadPath = 'archivos/reportador_incidencias/';
						$config['upload_path'] = $uploadPath;
						$config["file_name"] = url_title($_FILES['files']['name'][$i]);
						$config['allowed_types'] = 'gif|jpg|jpeg|png|pdf|doc|docx|xls|xlsx|bmp|ppt|pptx|csv';
						$this->load->library('upload', $config);
						$this->upload->initialize($config);

						// Upload file to server
						if ($this->upload->do_upload('file')) {
							// Uploaded file data
							$fileData = $this->upload->data();
							$uploadData[$i]['nev_archivo'] = $fileData['file_name'];
							$uploadData[$i]['lms_nota_caso_not_id'] = $idnota;
						} else {
							// echo $data['error_msg'] = $this->upload->display_errors();exit();
						}
					}
					if (!empty($uploadData)) {
						//print_r($uploadData);
						// Insert files data into the database
						$this->db->insert_batch("lms_nota_evidencias", $uploadData);
					}
					$this->m_mensajes_incidencias->MensajeNotaCreada($idnota);
					echo "<p class='alert alert-success'>Registro Completo. </p><script>location.reload()</script>";

					//Validamos si el caso es escalado a los gestores de semillas

				} else {
					echo "<p class='alert alert-danger'>Ha ocurrido un error. Intente de nuevo.</p>";
				}
			}
		}
	}

	public function encuesta($id)
	{
		if ($id) {
			redirect("gestion_encuestas/encuesta/$id");
		}
	}

	public function file_check($str)
	{
		$bandera = true;
		$allowed_mime_type_arr = array('doc', 'docx', 'ppt', 'pptx', 'gif', 'png', 'jpg', 'bmp', 'jpeg', 'xls', 'xlsx', 'pdf', 'GIF', 'PNG', 'JPG', 'csv', 'CSV');
		$filesCount = count($_FILES['files']['name']);
		// print_r($_FILES);
		for ($i = 0; $i < $filesCount; $i++) {
			$nombre = $_FILES['files']['name'][$i];
			$tam    = $_FILES['files']['size'][$i];
			$mime   = pathinfo($nombre, PATHINFO_EXTENSION);;
			//echo "NOMBRE: $nombre $tam";

			if (isset($nombre) && $nombre != "") {
				if (in_array($mime, $allowed_mime_type_arr)) {
				} else {
					$this->form_validation->set_message('file_check', 'El tipo de archivo ' . print_r($mime) . ' de ' . $nombre . ' es inválido.');
					$bandera = false;
				}
				if ($tam  > 2000000 ||  $tam  == 0) {
					$bandera = false;
					$this->form_validation->set_message('file_check', 'El tamaño de ' . $nombre . ' es inváido.');
					// return false;				
				}
			} else {
				//	$this->form_validation->set_message('file_check', 'Please choose a file to upload.');
				$bandera = true;
			}
		}
		return $bandera;
	}

	public function file_check_required($str)
	{
		$bandera = true;
		$allowed_mime_type_arr = array('doc', 'docx', 'ppt', 'pptx', 'gif', 'png', 'jpg', 'bmp', 'jpeg', 'xls', 'xlsx', 'pdf', 'GIF', 'PNG', 'JPG', 'csv', 'CSV');
		$filesCount = count($_FILES['files']['name']);
		// print_r($_FILES);
		for ($i = 0; $i < $filesCount; $i++) {
			$nombre = $_FILES['files']['name'][$i];
			$tam    = $_FILES['files']['size'][$i];
			$mime   = pathinfo($nombre, PATHINFO_EXTENSION);;
			//echo "NOMBRE: $nombre $tam";

			if (isset($nombre) && $nombre != "") {
				if (in_array($mime, $allowed_mime_type_arr)) {
				} else {
					$this->form_validation->set_message('file_check_required', 'El tipo de archivo ' . print_r($mime) . ' de ' . $nombre . ' es inválido.');
					$bandera = false;
				}
				if ($tam  > 2000000 ||  $tam  == 0) {
					$bandera = false;
					$this->form_validation->set_message('file_check_required', 'El tamaño de ' . $nombre . ' es inváido.');
					// return false;

				}
			} else {
				$this->form_validation->set_message('file_check_required', 'Suba por lo menos un archivo de evidencia o 
				complete el campo de link de evidencia con una ruta válida a Google Drive, One Drive, Dropbox o su servicio de almacenamiento en la nube preferido. ');
				$bandera = false;
			}
		}
		return $bandera;
	}

	/* Modulos JJPB */
	public function CargarTipoCaso()
	{
		$id_cat = $this->input->post('id_categoria');
		$qry = $this->db->query("SELECT * FROM lms_tipo_caso WHERE tca_estado LIKE 'DISPONIBLE' AND lms_herramientaslms_her_id=" . $id_cat);
		$row = $qry->result_array();
		foreach ($row as $dt) {
			echo '<option value="' . $dt['tca_id'] . '">' . $dt['tca_nombre'] . '</option>';
		}
	}

	public function ActualizarTipoCaso()
	{
		//Cargar los datos para ingresar la informacion de forma masiva
		$id_hr_new = $this->input->post('categoria', TRUE);
		$nm_hr_out = $this->input->post('her_nm_old', TRUE);
		$ds_tc_out = $this->input->post('tcs_nm_old', TRUE);
		$id_tc_new = $this->input->post('tipo', TRUE);
		$sop_id    = $this->input->post('sop_id', TRUE);

		if ($id_tc_new != 0) {
			$rst1 = $this->db->query("SELECT tca_nombre FROM lms_tipo_caso WHERE tca_id IN (" . $id_tc_new . ")")->result_array();
			$rst2 = $this->db->query("SELECT * FROM lms_nota_caso WHERE lms_caso_sop_id IN (" . $sop_id . ") ORDER BY not_id DESC")->result_array();
			$rst3 = $this->db->query("SELECT her_nombre FROM lms_herramientaslms WHERE her_id IN (" . $id_hr_new . ")")->result_array();
			$nota = "<p>Estimad@ Usuario.<br/><br/>El agente de soporte técnico de LMS ha realizado el análisis del caso que ha registrado, como resultado, se hizo necesario <strong>la recategorización del Tipo de Caso</strong> del que Usted registro como [<strong>" . $ds_tc_out . "</strong> / <em>" . $nm_hr_out . "</em>] a [<strong>" . $rst1[0]['tca_nombre'] . "</strong> / <em>" . $rst3[0]['her_nombre'] . "</em>].<br/><br/>De esta forma, estaremos brindando un mejor servicio a su solicitud.</p>";

			//Configuracion zona horaria local
			date_default_timezone_set('America/Bogota');
			$now = date('Y-m-d H:i:s');

			//Actualizamos el caso con el nuevo tipo seleccionado
			$this->db->query("UPDATE lms_caso SET lms_tipo_caso_tca_id = '" . $id_tc_new . "' WHERE sop_id = " . $sop_id);

			//Insertamos la nota de la actualizacion en cada programacion de fichas
			$this->db->set('not_id', 'NULL');
			$this->db->set('not_texto', $nota);
			$this->db->set('not_fecha', $now);
			$this->db->set('not_acceso', 'NULL');
			$this->db->set('not_estado_anterior', $rst2[0]['not_estado_anterior']);
			$this->db->set('not_estado_nuevo', $rst2[0]['not_estado_nuevo']);
			$this->db->set('usuario_usr_id', $this->id_usuario);
			$this->db->set('lms_caso_sop_id', $sop_id);
			$this->db->set('not_automatica', '0');
			$this->db->insert('lms_nota_caso');

			redirect("reportador_incidencias/ver_caso/" . $sop_id);
		} else {
			echo '<script language="javascript">alert("Error! No ha seleccionado un Tipo de Caso para poder actualizar el Incidente No. ' . $sop_id . '"); return false;</script>';
			redirect("reportador_incidencias/ver_caso/" . $sop_id);
		}
	}
}
