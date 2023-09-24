<!DOCTYPE html>
<html>
<head>
    <title>Formulário de Cifra e Decifra</title>
</head>
<style>

*, *:before, *:after { 
  margin:0;
  padding:0;
  font-family: Arial,sans-serif;
}

body{
  margin:10px;
}

a{
  text-decoration: none;
}

.content{
  width: 500px;
  margin: 0px auto;
  position: relative;   
}

h1{
  font-size: 18px;
  color: #066a75;
  padding: 10px 0;
  font-family: Arial,sans-serif;
  font-weight: bold;
  text-align: center;
  padding-bottom: 30px;
}

h1:after{
  content: ' ';
  display: block;
  width: 100%;
  height: 2px;
  margin-top: 10px;
  background: -webkit-linear-gradient(left, rgba(147,184,189,0) 0%,rgba(147,184,189,0.8) 20%,rgba(147,184,189,1) 53%,rgba(147,184,189,0.8) 79%,rgba(147,184,189,0) 100%); 
  background: linear-gradient(left, rgba(147,184,189,0) 0%,rgba(147,184,189,0.8) 20%,rgba(147,184,189,1) 53%,rgba(147,184,189,0.8) 79%,rgba(147,184,189,0) 100%); 
}

p {
  margin-bottom:15px;
}

p:first-child{
  margin: 0px;
}

label{
  color: #405c60;
  position: relative;
}

textarea.pre {
  font-family: "Courier New", monospace;
  white-space: pre-wrap;
  overflow-x: auto;
  overflow-y: auto;
  resize: both;
  border: 1px solid #ccc;
  background-color: #000;
  color: #fff;
  width: calc(100% - 20px);
  height: 200px;
  padding: 10px;
  outline: none;
}


::-webkit-input-placeholder  {
  color: #bebcbc; 
  font-style: italic;
}
input:-moz-placeholder,
textarea:-moz-placeholder{
  color: #bebcbc;
  font-style: italic;
} 
input {
  outline: none;
}

input:not([type="checkbox"]){
  width: 95%;
  margin-top: 4px;
  padding: 10px;    
  border: 1px solid #b2b2b2;
  
  -webkit-border-radius: 3px;
  border-radius: 3px;
  
  -webkit-box-shadow: 0px 1px 4px 0px rgba(168, 168, 168, 0.6) inset;
  box-shadow: 0px 1px 4px 0px rgba(168, 168, 168, 0.6) inset;
  
  -webkit-transition: all 0.2s linear;
  transition: all 0.2s linear;
}

input[type="submit"]{
  width: 50%!important;
  cursor: pointer;  
  background: #3d9db3;
  padding: 8px 5px;
  color: #fff;
  font-size: 20px;  
  border: 1px solid #fff;   
  margin-bottom: 10px;  
  text-shadow: 0 1px 1px #333;
  -webkit-border-radius: 5px;
  border-radius: 5px;  
  transition: all 0.2s linear;
}

input[type="submit"]:nth-of-type(1) {
  float: left;
}


input[type="submit"]:hover{
  background: #4ab3c6;
}

/* estilos para para ambos os formulários */
#openssl-encrypt{
  position: absolute;
  top: 0px;
  width: 88%;   
  padding: 18px 6% 60px 6%;
  margin: 0 0 35px 0;
  background: rgb(247, 247, 247);
  border: 1px solid rgba(147, 184, 189,0.8);
  
  -webkit-box-shadow: 5px;
  border-radius: 5px;
  
  -webkit-animation-duration: 0.5s;
  -webkit-animation-timing-function: ease;
  -webkit-animation-fill-mode: both;

  animation-duration: 0.5s;
  animation-timing-function: ease;
  animation-fill-mode: both;
}

#paracadastro:target ~ .content #openssl-encrypt{
  z-index: 2;
  -webkit-animation-name: fadeInLeft;
  animation-name: fadeInLeft;

  -webkit-animation-delay: .1s;
  animation-delay: .1s;
}
#paralogin:target ~ .content #openssl-encrypt{
  -webkit-animation-name: fadeOutLeft;
  animation-name: fadeOutLeft;
}
</style>
<body>

<?php

function encryptAES($plaintext, $key) {
    // $cipher: Uma string que representa o tipo de algoritmo de cifra a ser usado (AES-256-CBC)
    $cipher = "AES-256-CBC";
    
    // $ivlen (int): armazena o tamanho do vetor de inicialização (IV) para o algoritmo de cifra AES-256-CBC
    $ivlen = openssl_cipher_iv_length($cipher);
    
    // $iv (string/bytes): que armazena o vetor de inicialização (IV) aleatório
    $iv = openssl_random_pseudo_bytes($ivlen);
    
    // $ciphertext_raw (string/bytes): que armazena o texto cifrado
    $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
    
    // $hmac (string/bytes): que armazena o HMAC (hash-based message authentication code) do texto cifrado
    $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
    
    // Retorno: Uma string em base64 que inclui o IV, HMAC e o texto cifrado
    return base64_encode($iv.$hmac.$ciphertext_raw);
}


// Função para descriptografar um texto cifrado usando AES
function decryptAES($ciphertext, $key) {
    $cipher = "AES-256-CBC";
    
    // $ciphertext (string): O texto cifrado, codificado em Base64
    $ciphertext = base64_decode($ciphertext);
    
    // $ivlen (int): Comprimento do vetor de inicialização (IV)
    $ivlen = openssl_cipher_iv_length($cipher);
    
    // $iv (string/bytes): O vetor de inicialização extraído do texto cifrado
    $iv = substr($ciphertext, 0, $ivlen);
    
    // $hmac (string/bytes): Valor HMAC extraído do texto cifrado para verificação de integridade
    $hmac = substr($ciphertext, $ivlen, $sha2len=32);
    
    // $ciphertext_raw (string/bytes): O texto cifrado real após a remoção do IV e HMAC
    $ciphertext_raw = substr($ciphertext, $ivlen+$sha2len);
    
    // $original_plaintext (string|false): O texto descriptografado ou false em caso de falha
    $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
    
    // $calcmac (string/bytes): HMAC calculado do texto cifrado descriptografado
    $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
    
    // Verificação de igualdade HMAC
    if (hash_equals($hmac, $calcmac)) {
        
        // Retorna o texto plano se o HMAC calculado e o fornecido forem iguais
        return $original_plaintext;
    }
}


?>

<div class="container" >    
    <div class="content">      
      <div id="openssl-encrypt">
        <form method="post" action=""> 
          <h1>Projeto de Criptografia AES e <br/>HMAC com PHP</h1> 
          
          <p> 
            <label for="key">Texto</label>
            <input id="text" name="text" required="required" type="text"/>
          </p>
          
          <p> 
            <label for="key">Chave</label>
            <input id="key" name="key" value="<?php echo isset($_POST['key']) ? $_POST['key'] : 'B27C54EA1365F1F5692DD89E7A827C82' ?>" required="required" type="text"/>
          </p>
          
            <?php

                if (isset($_POST['encrypt'])) {
                    echo '<textarea class="pre">';
                    $text = $_POST['text'];
                    $key = $_POST['key'];
                    $encrypted = encryptAES($text, $key);
                    echo "Texto cifrado: $encrypted";
                    echo '</textarea>';
                } elseif (isset($_POST['decrypt'])) {
                    echo '<textarea class="pre">';
                    $text = $_POST['text'];
                    $key = $_POST['key'];
                    $decrypted = decryptAES($text, $key);
                    if (empty($decrypted)) {
                        $decrypted = 'Erro ao decifrar';
                    }
                    echo "Texto decifrado: $decrypted";
                    echo '</textarea>';
                }
            ?>
          
          <p> 
            <input type="submit" name="encrypt" value="Cifrar"/>
            <input type="submit" name="decrypt" value="Decifrar"/>          
          </p>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
