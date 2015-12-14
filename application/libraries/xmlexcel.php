<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Code Igniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		Rick Ellis
 * @copyright	Copyright (c) 2006, pMachine, Inc.
 * @license		http://www.codeignitor.com/user_guide/license.html
 * @link		http://www.codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

class Xmlexcel {
	

	function Xmlexcel()
	{
		$this->init();
	}
	
	function init(){
		$this->rows = '';
	}
	
	function write_header(){
		$this->header = 
				'<?xml version="1.0"?>
				<?mso-application progid="Excel.Sheet"?>
				<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
				 xmlns:o="urn:schemas-microsoft-com:office:office"
				 xmlns:x="urn:schemas-microsoft-com:office:excel"
				 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
				 xmlns:html="http://www.w3.org/TR/REC-html40">';

	}
	
	function write_document_properties($author){
		$this->docprop = 
				'<DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
				  <Author>'.$author.'</Author>
				  <LastAuthor>'.$author.'</LastAuthor>
				  <Created>'.date('Y-m-d\TH:i:s\Z').'</Created>
				  <Version>11.5606</Version>
				 </DocumentProperties>';

	}
	
	function write_styles(){
		$this->styles = 
			'<Styles>
			  <Style ss:ID="Default" ss:Name="Normal">
			   <Alignment ss:Vertical="Bottom"/>
			   <Borders/>
			   <Font x:CharSet="238"/>
			   <Interior ss:Color="#ffffff" ss:Pattern="Solid"/>
			   <NumberFormat/>
			   <Protection/>

			  </Style>
			  <Style ss:ID="BoldTitle">
			   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
			   <Borders/>
			   <Font ss:Bold="1"/>
			   <Interior ss:Color="#E8D0C5" ss:Pattern="Solid"/>
			   <NumberFormat/>
			   <Protection/>

			  </Style>
			  <Style ss:ID="headerStyle">
			   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
			   <Borders>
			    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
			    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
			    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
			    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
			   </Borders>
			   <Font ss:Bold="1"/>
			   <Interior ss:Color="#E8DFC5" ss:Pattern="Solid"/>

			  </Style>


              <Style ss:ID="pinkCell">
			   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
			   <Borders>
			    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
			    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
			    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
			    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
			   </Borders>
			   <Interior ss:Color="#FFEFF7" ss:Pattern="Solid"/>
			  </Style>
              <Style ss:ID="blueCell">
			   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
			   <Borders>
			    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
			    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
			    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
			    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
			   </Borders>
			   <Interior ss:Color="#DFEBFF" ss:Pattern="Solid"/>
			  </Style>
			 </Styles>';

	}
	
	function write_footer(){
		$this->footer = '</Workbook>';
	}
	
	function start_worksheet(){
		$this->ws = '<Worksheet ss:Name="Sheet1">
  					  <Table>';
	}
	
    function xml_from_query($query, $author){
        $this->write_header();
		$this->write_document_properties($author);
		$this->write_styles();
		$this->start_worksheet();

        $this->rows .='<Row>';
		foreach ($query->list_fields() as $name)
		{

            $this->rows .='<Cell ss:StyleID="headerStyle"><Data ss:Type="String">'.$name.'</Data></Cell>';
		}
        $this->rows .='</Row>';   
		
        
		foreach ($query->result_array() as $row)
		{
		    $this->rows .='<Row>';
			foreach ($row as $item)
			{
			
                $this->rows .='<Cell ss:StyleID="blueCell"><Data ss:Type="String">'.$item.'</Data></Cell>';
			}
            $this->rows .='</Row>';
		}
        $this->end_worksheet();
		$this->write_footer();
        return $this->output();
        
    }
    
	function write_row($row){
		$this->rows .= $row;
	}
	
	function end_worksheet(){
		$this->we = ' </Table>
					</Worksheet>';
	}
	
	function output(){
		return $this->header.$this->docprop.$this->styles.$this->ws.$this->rows.$this->we.$this->footer;
	}
}

?>