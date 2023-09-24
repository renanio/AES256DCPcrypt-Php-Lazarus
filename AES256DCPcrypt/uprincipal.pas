unit uPrincipal;

{$mode objfpc}{$H+}

interface

uses
  Classes, SysUtils, Forms, Controls, Graphics, Dialogs, StdCtrls, DCPcrypt2,
  DCPblockciphers, DCPrijndael, DCPsha256, Base64;

type

  { TfrmPrincipal }

  TfrmPrincipal = class(TForm)
    btnDecrypt: TButton;
    edtDecryptPassword: TEdit;
    edtDecrypt: TEdit;
    labelTexto: TLabel;
    labelSenha: TLabel;
    labelDecifrar: TLabel;
    memoDecrypted: TMemo;
    procedure btnDecryptClick(Sender: TObject);
    procedure FormCreate(Sender: TObject);
  private
    function decryptAES(ciphertext: AnsiString; Password: AnsiString): AnsiString;
  public

  end;

var
  frmPrincipal: TfrmPrincipal;

implementation

{$R *.lfm}

{ TfrmPrincipal }

procedure TfrmPrincipal.btnDecryptClick(Sender: TObject);
var
  DecryptedText: string;
begin
  if edtDecrypt.Text = '' then
  begin
    ShowMessage('É necessário informar um texto');
    Exit;
  end;

  if edtDecryptPassword.Text = '' then
  begin
    ShowMessage('A senha não pode estar vazia.');
    Exit;
  end;

  DecryptedText := decryptAES(edtDecrypt.Text, edtDecryptPassword.Text);
  memoDecrypted.Text := '';
  memoDecrypted.Lines.Add(DecryptedText); // Apenas o texto descriptografado
end;

procedure TfrmPrincipal.FormCreate(Sender: TObject);
var
  Password: String;
begin
  Password := 'B27C54EA1365F1F5692DD89E7A827C82';
  edtDecryptPassword.Text := Password;
end;

function TfrmPrincipal.decryptAES(ciphertext: AnsiString; Password: AnsiString): AnsiString;
var
  cipher: TDCP_rijndael;
  sha256: TDCP_sha256;
  hmac: AnsiString;
  iv, ciphertext_raw, original_plaintext: AnsiString;
  ivlen, DataLen, PaddingLen: integer;
begin
  Result := '';  // Inicializa o resultado como uma string vazia
  original_plaintext := ''; // Inicializa original_plaintext

  if Password = '' then
  begin
    Result := 'Chave vazia';
    Exit;
  end;

  try
    // Decodifica o texto cifrado Base64 para dados binários
    ciphertext := DecodeStringBase64(ciphertext);
  except
    on E: Exception do
    begin
      Result := 'Erro ao decodificar Base64: ' + E.Message;
      Exit;
    end;
  end;

  // Define o comprimento do IV (Initialization Vector)
  ivlen := 16;

  if Length(ciphertext) < (ivlen + 33) then
  begin
    Result := 'Texto cifrado inválido';
    Exit;
  end;

  // Tenta extrair o IV e o HMAC
  try
    iv := Copy(ciphertext, 1, ivlen);
    hmac := Copy(ciphertext, ivlen + 1, 32);
    ciphertext_raw := Copy(ciphertext, ivlen + 33, Length(ciphertext));
  except
    on E: Exception do
    begin
      Result := 'Erro ao extrair IV ou HMAC: ' + E.Message;
      Exit;
    end;
  end;

  // Inicializa o algoritmo AES e tenta descriptografar
  cipher := TDCP_rijndael.Create(nil);
  try
    cipher.Init(Password[1], Length(Password) * 8, @iv[1]);
    SetLength(original_plaintext, Length(ciphertext_raw));
    cipher.DecryptCBC(ciphertext_raw[1], original_plaintext[1], Length(ciphertext_raw));
    cipher.Burn;
  finally
    cipher.Free;
  end;

  // Remove o padding PKCS#7
  DataLen := Length(original_plaintext);
  if DataLen > 0 then
  begin
    PaddingLen := Ord(original_plaintext[DataLen]);
    if (PaddingLen > 0) and (PaddingLen <= DataLen) then
    begin
      original_plaintext := Copy(original_plaintext, 1, DataLen - PaddingLen);
    end;
  end;

  // Retorna o texto descriptografado
  Result := original_plaintext;
end;



end.

