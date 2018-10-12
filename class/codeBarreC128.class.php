<?php
class codeBarreC128 {
	// Definition de l'algo pour un type C128
	private $typeC128 = array(
					0		=> '11011001100',
					1		=> '11001101100',
					2		=> '11001100110',
					3 		=> '10010011000',
					4 		=> '10010001100',
					5 		=> '10001001100',
					6 		=> '10011001000',
					7		=> '10011000100',
					8 		=> '10001100100',
					9 		=> '11001001000',
					10 		=> '11001000100',
					11 		=> '11000100100',
					12 		=> '10110011100',
					13 		=> '10011011100',
					14 		=> '10011001110',
					15 		=> '10111001100',
					16 		=> '10011101100',
					17 		=> '10011100110',
					18 		=> '11001110010',
					19 		=> '11001011100',
					20 		=> '11001001110',
					21 		=> '11011100100',
					22 		=> '11001110100',
					23 		=> '11101101110',
					24 		=> '11101001100',
					25 		=> '11100101100',   
					26 		=> '11100100110',
					27 		=> '11101100100',
					28 		=> '11100110100',
					29 		=> '11100110010',
					30 		=> '11011011000',
					31 		=> '11011000110',
					32 		=> '11000110110',
					33 		=> '10100011000',
					34 		=> '10001011000',
					35 		=> '10001000110',
					36 		=> '10110001000',
					37 		=> '10001101000',
					38 		=> '10001100010',
					39 		=> '11010001000',
					40 		=> '11000101000',
					41 		=> '11000100010',
					42 		=> '10110111000',
					43 		=> '10110001110',
					44 		=> '10001101110',
					45 		=> '10111011000',
					46 		=> '10111000110',
					47 		=> '10001110110',
					48 		=> '11101110110',
					49 		=> '11010001110',
					50 		=> '11000101110',
					51 		=> '11011101000',
					52 		=> '11011100010',
					53 		=> '11011101110',
					54 		=> '11101011000',
					55 		=> '11101000110',
					56 		=> '11100010110',
					57 		=> '11101101000',
					58 		=> '11101100010',
					59 		=> '11100011010',
					60 		=> '11101111010',
					61 		=> '11001000010',
					62 		=> '11110001010',
					63 		=> '10100110000',
					64 		=> '10100001100',
					65 		=> '10010110000',
					66 		=> '10010000110',
					67 		=> '10000101100',
					68 		=> '10000100110',
					69 		=> '10110010000',
					70 		=> '10110000100',
					71 		=> '10011010000',
					72 		=> '10011000010',
					73 		=> '10000110100',
					74 		=> '10000110010',
					75 		=> '11000010010',
					76 		=> '11001010000',
					77 		=> '11110111010',
					78 		=> '11000010100',
					79 		=> '10001111010',
					80 		=> '10100111100',
					81 		=> '10010111100',
					82 		=> '10010011110',
					83 		=> '10111100100',
					84 		=> '10011110100',
					85 		=> '10011110010',
					86 		=> '11110100100',
					87 		=> '11110010100',
					88 		=> '11110010010',
					89 		=> '11011011110',
					90 		=> '11011110110',
					91 		=> '11110110110',
					92 		=> '10101111000',
					93 		=> '10100011110',
					94		=> '10001011110',
					95 		=> '10111101000',
					96		=> '10111100010',
					97 		=> '11110101000',
					98 		=> '11110100010',
					99  	=> '10111011110',    // 99 et 'c' sont identiques ne nous sert que pour le checksum
					100 	=> '10111101110',    // 100 et 'b' sont identiques ne nous sert que pour le checksum
					101 	=> '11101011110',    // 101 et 'a' sont identiques ne nous sert que pour le checksum
					102 	=> '11110101110',    // 102 correspond à FNC1 ne nous sert que pour le checksum
					'c' 	=> '10111011110',
					'b' 	=> '10111101110',
					'a' 	=> '11101011110',
					'A' 	=> '11010000100',
					'B' 	=> '11010010000',
					'C'		=> '11010011100',
					'S'		=> '1100011101011'
			);
	private $height = 10;	
	private $width;
	private $orientation = 'horizontal';
	
	private $format = 'GIF';
	private $authorizedFormat = array('PNG', 'GIF', 'JPG', 'JPEG');
	
	private $sourceCode;
	private $barTitle = false;
	private $framedTitle = false;
	private $code = array();
	private $strCode;
	
	private $couleurRGBFill = array(0,0,0);
	private $couleurRGBBlank = array(255,255,255);
	
	const ERROR_UNAUTHORIZED_FORMAT = 'Le format que vous avez selectionne n\'est pas autorise !';
	const ERROR_UNKNOWN_ORIENTATION = 'L\'orientation que vous avez choisie est invalide !';
	const ERROR_MAKE_FILE = 'Impossible de creer le fichier. Verifier que vous avez les droits dans le dossier cible !';
   
	// CONSTRUCTEUR
	public function __construct($code) {
		settype($code,'string');
		$this->sourceCode = $code;
		$nbKr = strlen($code);
	    $strCode = '';

        for( $i=0; $i<$nbKr; $i++ ) {
            $this->code[$i] = substr($code, $i, 1);
        }

		$strCode = $this->typeC128['B']; // Start
		$checksum = 104 ;
		$j = 1 ;

		for( $i=0; $i<$nbKr; $i++ ) {
			$tmp = ord($this->code[$i]) - 32 ;
			$checksum += ( $j++ * $tmp ) ;
			$strCode .= $this->typeC128[$tmp];
		}

		$checksum %= 103 ;
		$strCode .= $this->typeC128[$checksum];
		$strCode .= $this->typeC128['S']; // Stop

        $this->strCode = $strCode;
		$tmp = strlen($this->strCode) + 20;

		$this->width = $tmp;
	}
	public function setFramedTitle($frame) {
		$this->framedTitle = $frame;	
	}
	public function setTitle($title = false) {
		if( !$title ) {
			$this->barTitle = $this->sourceCode;
		} else {
			$this->barTitle = $title;
		}
	}
	public function setOrientation($orientation) {
		$orientation = strtolower($orientation);
		
		if( $orientation == 'h' or $orientation == 'horizontal' ) {
			$this->orientation = 'horizontal';
		} elseif( $orientation == 'v' or $orientation == 'vertical' ) {
			$this->orientation = 'vertical';
		} else {
			throw new Exception(self::ERROR_UNKNOWN_ORIENTATION);
		}
	}
	public function setColor($level, $hexaColor) {
		$hexaColor = str_replace('#', '', $hexaColor);
		
		$red = hexdec(substr($hexaColor, 0, 2));
		$green = hexdec(substr($hexaColor, 2, 2));
		$blue = hexdec(substr($hexaColor, 4, 2));
		
		if( $level == 'Fill' ) {
			$this->couleurRGBFill = array($red, $green, $blue);
		} elseif( $level == 'Blank' ) {
			$this->couleurRGBBlank = array($red, $green, $blue);
		} else {
			return false;	
		}
	}
	public function setFormat($format) {
		$format = strtoupper($format);

		if( in_array($format, $this->authorizedFormat) ) {
			$this->format = $format;
		} else {
			throw new Exception(self::ERROR_UNAUTHORIZED_FORMAT);
		}
	}
	public function setHeight($height) {
		$this->height = $height;   
	}
	public function Output($fileName = false) {
		$posX = 10; // position X
        $posY = 0; // position Y
        $intL = 1; // largeur de la barre
		$nbKr = strlen($this->strCode);
		
		$codeBarre = imagecreate($this->width, $this->height);

		$color['Fill'] = imagecolorallocate($codeBarre, $this->couleurRGBFill[0], $this->couleurRGBFill[1], $this->couleurRGBFill[2]);
		$color['Blank'] = imagecolorallocate($codeBarre, $this->couleurRGBBlank[0], $this->couleurRGBBlank[1], $this->couleurRGBBlank[2]);
		
		imagefilledrectangle($codeBarre, 0, 0, $this->width, $this->height, $color['Blank']);
        
		if( $this->barTitle !== false ) {
			$test = imagefontwidth(3);
			imagestring($codeBarre, 3, floor(($this->width/2)) - (($test * strlen($this->barTitle)) / 2), $this->height - 13, $this->barTitle, $color['Fill']);
		}
		
		for( $i=0; $i<$nbKr; $i++ ) {
			$intH = $this->height;
			
			if( !$this->framedTitle && $this->barTitle !== false ) {
				$intH -= 14;
			} else {
				if( $this->barTitle !== false && $this->framedTitle && $i > 11 && $i < ($nbKr-13) ) {
					$intH -= 14;
				}
			}
			
			if( substr($this->strCode,$i,1) ) {
				imagefilledrectangle($codeBarre, $posX, $posY, $posX, ($posY+$intH), $color['Fill']);
			}
			
            $posX += $intL;
		}
		
		if( $this->orientation == 'vertical' ) {
			$codeBarre = imagerotate($codeBarre, 90, $color['Blank']);	
		}
		
		if( !$fileName ) {
			if( $this->format == 'PNG' ) {		
				header('Content-Type: image/png'); 	
				imagepng($codeBarre); 
			} elseif( $this->format == 'GIF' ) {
				header('Content-Type: image/gif'); 
				imagegif($codeBarre); 
			} elseif( $this->format == 'JPG' or $this->format == 'JPEG' ) {
				header('Content-Type: image/jpeg'); 
				imagejpeg($codeBarre); 
			} 
		} else {	
			if( $this->format == 'PNG' ) {	
				$generated = imagepng($codeBarre, $fileName); 
			} elseif( $this->format == 'GIF' ) {
				$generated = imagegif($codeBarre, $fileName); 
			} elseif( $this->format == 'JPG' or $this->format == 'JPEG' ) {
				$generated = imagejpeg($codeBarre, $fileName); 
			} 
			
			if( !$generated ) {
				throw new Exception(self::ERROR_MAKE_FILE);
			} else {
				return true;
			}
		}
		
		imagedestroy($codeBarre);
	}
}
?>