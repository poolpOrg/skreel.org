<?php

/*
 * Copyright (c) 1989, 1993
 *      Chehade Gilles.  All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. All advertising materials mentioning features or use of this software
 *    must display the following acknowledgement:
 *      This product includes software developed by the University of
 *      California, Berkeley and its contributors.
 * 4. Neither the name of the University nor the names of its contributors
 *    may be used to endorse or promote products derived from this software
 *    without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE REGENTS AND CONTRIBUTORS ``AS IS'' AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED.  IN NO EVENT SHALL THE REGENTS OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
 * OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 * OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 * SUCH DAMAGE.
 *
 * --
 * veins (http://www.skreel.org/~veins/) <veins@skreel.org>
 * with the help of k` <agggka@hotmail.com>
 * --
 *
 * to use the library, include this file into any script that requires access
 * to the encryption and decryption functions, then use as follows:
 *
 *	$encrypted = SK_encrypt($key, $clear, CBC);
 *	$decrypted = SK_decrypt($key, $crypt, CBC);
 */


/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */


 /* some useful defines... */
   define('ECB'		, 0);	// Electronic Code Book
   define('CBC'		, 1);	// Cipher Block Chaining (emulates blocks)
   define('SK_ENCRYPT'	, 0);
   define('SK_DECRYPT'	, 1);

 // mode utilise par defaut
   define('CURRENT'	, CBC);

 // force de la cle
   define('KEY_STRENGHT', 128);


 /* ! WARNING ! KEY_STRENGHT
  * 
  * the implementation supports dynamic key resizing which prevents statistic 
  * attacks _even in ECB mode_. it will build a minimal key of 256 bits with 
  * no apparent pattern matching. this mode _cannot_ be used in some countries 
  * that's why it is disabled by default. to enable dynamic key resizing, turn 
  * KEY_STRENGHT to a value of 0. 
  */


/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */


 /**
  * Chiffrement suivant le mode choisi.
  * @param $key			cle de chiffrement (chaine de caractere)
  * @param $data		donnees a chiffrer (chaine de caractere)
  * @param $mode		mode de chiffrement (CBC ou ECB)
  * @return			chaine $data chiffree avec la cle $key
  */
   Function SK_encrypt($key, $data, $mode= CURRENT)
   { if ( !(is_string($key) && is_string($data) && is_integer($mode)) )
       return( NULL );

     $keyLong= SK_buildkey($key, strlen($data));
     $keySize= strlen($keyLong);

     /* $data is padded with NULL bytes to match the key's size */
     $retour= str_pad($data, $keySize, chr(0));

     /* we loop on $keySize since the data is padded with NULL bytes */
     for ($i=0 ; $i<$keySize ; $i++)
       $retour[$i]= SK_substitute($keyLong[$i], $retour[$i], SK_ENCRYPT);

     $retour= ($mode==ECB) ? $retour : SK_CBC($retour, $keySize, SK_ENCRYPT) ;
     return( SK_str2hex($retour) );
   }


 /**
  * Dechiffrement suivant le mode choisi.
  * @param $key			cle de chiffrement (chaine de caractere)
  * @param $data		donnees a dechiffrer (chaine de caractere)
  * @param $mode		mode de chiffrement (CBC ou ECB)
  * @return			chaine $data dechiffree avec la cle $key
  */
   function SK_decrypt($key, $data, $mode= CURRENT)
   { if ( !(is_string($key) && is_string($data) && is_integer($mode)) )
       return( NULL );

     $dataSize= strlen($data) / 2;
     $retour= ($mode==ECB) ? SK_hex2str($data) : SK_CBC(SK_hex2str($data), $dataSize, SK_DECRYPT);
     $keyLong = SK_buildkey($key, $dataSize);

     for ($i=0 ; $i<$dataSize - 8; $i++)
       $retour[$i]= SK_substitute($keyLong[$i], $retour[$i], SK_DECRYPT);

     /* strip NULL bytes */
     return( trim($retour) );
   }


/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */


 /**
  * Construit a partir de la cle $key une cle qui ne possede pas
  *  de repetition de motifs, la longueur de la cle generee est
  *  superieure ou egale a la longueur des donnees a (de)chiffrer.
  * @param $key			cle initiale
  * @param $dlength		longueur des donnees
  * @return			nouvelle cle
  */
   function SK_buildkey($key, $dlength)
   { $retour= md5($key);

     /* if KEY_STRENGHT is null, we use dynamic key resizing to avoid
      * statistic attacks. the lowest key will be 256 bits.
      * else we create a base key of KEY_STRENGHT / 8 bytes and then
      * concatenate it to itself till it is $dlenght bytes long.
      */

     if (KEY_STRENGHT == 0)
     { while (strlen($retour) < $dlength)
         $retour.= md5($retour);
     }
     else
     { $strenght= KEY_STRENGHT/8;
       while ( strlen($retour) <= $strenght )
	 $retour.= md5($retour);
       $base= substr($retour, 0, $strenght);
       while ( strlen($retour) < $dlength )
	 $retour.= $base;
     }
     return( $retour );
   }


 /**
  * Generation d'un vecteur d'initialisation 'aleatoire'.
  *  Cette fonction n'est pas geniale, il doit y avoir un
  *  meilleur moyen d'obtenir de l'entropie (du desordre).
  * @return			une chaine de 8 caracteres
  */
   function SK_makeIV()
   { $retour= str_repeat(' ', 8);
     $retour[0]= chr( rand(1, 255) );
     $retour[1]= chr( rand(1, 255) );
     $retour[2]= chr( rand(1, 255) );
     $retour[3]= chr( rand(1, 255) );
     $retour[4]= chr( rand(1, 255) );
     $retour[5]= chr( rand(1, 255) );
     $retour[6]= chr( rand(1, 255) );
     $retour[7]= chr( rand(1, 255) );
     return( (string)$retour );
   }


/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */


 /**
  * Realise une substitution de caractere suivant la methode
  *  de Vigenere. Il n'y a pas de tableau de codage/decodage.
  * @param $char_k		caractere de la cle
  * @param $char_d		caractere de donnee
  * @param $mode		sens : chiffrement ou dechiffrement
  * @return			caractere resultant
  */
   function SK_substitute($char_k, $char_d, $mode)
   { $k= ord($char_k);
     $d= ord($char_d);

     if ($mode == SK_ENCRYPT)
       $retour= (($k + $d) < 256) ? $k + $d : $k + $d - 256 ;
     else
       $retour= (($d - $k) >= 0 ) ? $d - $k : $d - $k + 256 ;
     /*  $retour= ($k <= $d ? $d - $k : 256 - ($k - $d)); */

     return(chr($retour));
   }


/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */


 /**
  * Realise le chainage du mode CBC.
  * @param $data		donnees a dechiffrer (chaine de caractere)
  * @param $mode		sens : chiffrement ou dechiffrement
  * @return			donnees resultantes
  */
   function SK_CBC($data, $dlenght, $mode)
   { $retour= ($mode == SK_ENCRYPT) ? SK_makeiv().$data : $data ;

     if ($mode == SK_ENCRYPT)
       for ($i=1; $i<$dlenght + 8; $i++)
         $retour[$i]= chr( ( ord($retour[$i]) ^ ord($retour[$i - 1]) ) );
     else
       for ($i=$dlenght - 1 ; $i>=8 ; $i--)
         $retour[$i]= chr( ( ord($retour[$i]) ^ ord($retour[$i - 1]) ) );

     $retour= ($mode == SK_ENCRYPT) ? $retour : substr($retour, 8) ;

     return( $retour );
   }


/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */


 /**
  * Convertion hexadecimal => chaine de caractere.
  * @param $hexa		'chaine hexadecimale' a convertir
  * @return			chaine convertie
  */
   function SK_hex2str( $hexa )
   { $length= strlen($hexa);
     $retour= '';
     for ($i=0 ; $i<$length ; $i+=2)
       $retour.= chr(hexDec( substr($hexa, $i, 2) ));
     return( $retour );
   }


 /**
  * Convertion chaine caractere => hexadecimal.
  * @param $string		chaine de caractere a convertir
  * @return			'chaine hexadecimale'
  */
   function SK_str2hex( $string )
   { $length= strlen($string);
     $retour= '';
     for ($i=0 ; $i<$length ; $i++)
       $retour.= str_pad( decHex(ord($string[$i])), 2, '0', STR_PAD_LEFT );
//       $retour.= ( strlen(decHex(ord($string[$i]))) % 2 ) ? '0'.decHex( ord($string[$i]) ) : decHex( ord($string[$i]) ); 
     return( $retour ); 
   }
?>
