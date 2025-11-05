<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login - Clinic Help</title>
  <link rel="icon" href="clinichelpicon.png">
  <link rel="stylesheet" href="../css/login.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
<?php

include('conexao.php');
include("menu.php");
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cadastrar_clinica'])) {
  echo "Entrou no IF da clínica<br>";
flush();

    $senhaHash = password_hash($_POST['senha'], PASSWORD_DEFAULT); 
    $sqlClinica = "INSERT INTO clinicas_ (nome, CNPJ, telefone, Cidade, estado, email)
                   VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sqlClinica);
    if (!$stmt) {
        die("Erro ao preparar SQL da clínica: " . $conn->error);
    }
    $stmt->bind_param(
        "ssssss",
        $_POST['nome'],
        $_POST['cnpj'],
        $_POST['telefone'],
        $_POST['cidade'],
        $_POST['estado'],
        $_POST['email']
    );
    $stmt->execute();
    $stmt->close();

    $sqlUsuario = "INSERT INTO usuario (login, senha, tipo) VALUES (?, ?, ?)";
    $stmtUser = $conn->prepare($sqlUsuario);
    if (!$stmtUser) {
        die("Erro ao preparar SQL do usuário: " . $conn->error);
    }

    $tipo = "clinica";
    $stmtUser->bind_param("sss", $_POST['email'], $senhaHash, $tipo); 
    $stmtUser->execute();
    $stmtUser->close();
}



if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cadastrar_paciente'])) {
    $sqlPaciente = "INSERT INTO pacientes (Nome_paciente, telefone, CPF, CEP, email)
                    VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sqlPaciente);
    if (!$stmt) {
        die("Erro ao preparar SQL do paciente: " . $conn->error);
    }

    $senhaHash = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $stmt->bind_param(
        "sssss",
        $_POST['nome'],
        $_POST['telefone'],
        $_POST['cpf'],
        $_POST['cep'],
        $_POST['email']
    );
    $stmt->execute();
    $stmt->close();

    $sqlUsuario = "INSERT INTO usuario (login, senha, tipo) VALUES (?, ?, ?)";
    $stmtUser = $conn->prepare($sqlUsuario);
    if (!$stmtUser) {
        die("Erro ao preparar SQL do usuário (paciente): " . $conn->error);
    }

    $tipo = "paciente";
    $stmtUser->bind_param("sss", $_POST['email'], $senhaHash, $tipo);
    $stmtUser->execute();
    $stmtUser->close();
}
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cadastrar_medico'])) {
    $sqlMedico = "INSERT INTO medico (nome, email, telefone, crm, cpf, especializacao)
                    VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sqlMedico);
    if (!$stmt) {
        die("Erro ao preparar SQL do médico: " . $conn->error);
    }

    $senhaHash = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $stmt->bind_param(
        "ssssss",
        $_POST['nome'],
        $_POST['email'],
        $_POST['telefone'],
        $_POST['crm'],
        $_POST['cpf'],
        $_POST['especializacao']
    );
    $stmt->execute();
    $stmt->close();

    // ✅ Aqui sim o tipo é "medico"
    $sqlUsuario = "INSERT INTO usuario (login, senha, tipo) VALUES (?, ?, ?)";
    $stmtUser = $conn->prepare($sqlUsuario);
    if (!$stmtUser) {
        die("Erro ao preparar SQL do usuário (médico): " . $conn->error);
    }

    $tipo = "medico";
    $stmtUser->bind_param("sss", $_POST['email'], $senhaHash, $tipo);
    $stmtUser->execute();
    $stmtUser->close();
}



?>

<main class="main">
  <div class="container">
    <div class="tabs">
      <button id="btnLoginTab" class="active">Login</button>
      <button id="btnRegisterTab">Cadastro</button>
    </div>

    <div class="sub-tabs">
      <button id="pacienteTab" class="active">Paciente</button>
      <button id="clinicaTab">Clínica</button>
      <button id="medicoTab">Médico</button>
    </div>


     
    <form id="loginPacienteForm" class="active" action="processa_login.php" method="POST">
        <input type="hidden" name="tipo" value="paciente">
      <input type="email" name="login" placeholder="E-mail do Paciente" required>
      <input type="password" name="senha" placeholder="Senha" required>
      <button type="submit">Entrar como Paciente</button>
    </form>


    <form id="loginClinicaForm" action="processa_login.php" method="POST">
        <input type="hidden" name="tipo" value="clinica">
      <input type="email" name="login" placeholder="E-mail da Clínica" required>
      <input type="password" name="senha" placeholder="Senha" required>
      <button type="submit">Entrar como Clínica</button>
    </form>

    <form id="loginMedicoForm" action="processa_login.php" method="POST">
        <input type="hidden" name="tipo" value="medico">
      <input type="email" name="login" placeholder="E-mail do Médico" required>
      <input type="password" name="senha" placeholder="Senha" required>
      <button type="submit">Entrar como Médico</button>
    </form>


    <form id="cadastroPacienteForm" method="POST" action="">
      <input type="hidden" name="cadastrar_paciente" value="1">
      <input type="text" name="nome" placeholder="Nome Completo" required>
      <input type="email" name="email" placeholder="E-mail" required>
      <input type="password" name="senha" placeholder="Senha" required>
      <input type="text" class="telefone" name="telefone" placeholder="Telefone" required>
      <input type="text" class="cpf" name="cpf" placeholder="CPF" required>
      <input type="text" id="cep" name="cep" placeholder="CEP" required>
      <button type="submit">Cadastrar Paciente</button>
    </form>

  <form id="cadastroMedicoForm" method="POST" action="">
  <input type="hidden" name="cadastrar_medico" value="1">
  <input type="text" name="nome" placeholder="Nome Completo" required>
  <input type="email" name="email" placeholder="E-mail" required>
  <input type="password" name="senha" placeholder="Senha" required>
  <input type="text" name="telefone" placeholder="Telefone" required>
  <input type="text" name="crm" class="mask-crm" maxlength="11" autocomplete="off" placeholder="CRM" required>
  <input type="text" class="cpf" name="cpf" placeholder="CPF" required>
  <label for="especializacao">Especialização:</label>
  <select id="especializacao"  name="especializacao" required>
  <option value="">Selecione</option>
  <option value="Cardiologia">Cardiologia</option>
  <option value="Clínico Geral">Clínico Geral</option>
  <option value="Endocrinologia">Endocrinologia</option>
  <option value="Dermatologia">Dermatologia</option>
  <option value="Gastroenterologia">Gastroenterologia</option>
  <option value="Ginecologia">Ginecologia</option>
  <option value="Infectologia">Infectologia</option>
  <option value="Nefrologia">Nefrologia</option>
  <option value="Neurologia">Neurologia</option>
  <option value="Patologia">Patologia</option>
  <option value="Pediatria">Pediatria</option>
  <option value="Pneumologia">Pneumologia</option>
  <option value="Obstetrícia">Obstetrícia</option>
  <option value="Odontologia">Odontologia</option>
  <option value="Oftalmologia">Oftalmologia</option>
  <option value="Oncologia">Oncologia</option>
  <option value="Ortopedia">Ortopedia</option>
  <option value="Otorrinolaringologia">Otorrinolaringologia</option>
  <option value="Radioterapia">Radioterapia</option>
  <option value="Reumatologia">Reumatologia</option>
  <option value="Urologia">Urologia</option> 
  </select>
  <button type="submit">Cadastrar Médico</button>
</form>



    <form id="cadastroClinicaForm" method="POST" action="">
      <input type="hidden" name="cadastrar_clinica" value="1">
      <input type="text" name="nome" placeholder="Nome da Clínica" required>
      <input type="email" name="email" placeholder="E-mail" required>
      <input type="password" name="senha" placeholder="Senha" required>
      <input type="text" id="cnpj" name="cnpj" placeholder="CNPJ" required>
      <input type="text" class="telefone" name="telefone" placeholder="Telefone" required>

      <label for="estado">Estado:</label>
      <select id="estado" name="estado" onchange="carregarCidades()">
        <option value="">Selecione</option>
        <option value="AC">Acre</option>
        <option value="AL">Alagoas</option>
        <option value="AP">Amapá</option>
        <option value="AM">Amazonas</option>
        <option value="BA">Bahia</option>
        <option value="CE">Ceará</option>
        <option value="DF">Distrito Federal</option>
        <option value="ES">Espírito Santo</option>
        <option value="GO">Goiás</option>
        <option value="MA">Maranhão</option>
        <option value="MT">Mato Grosso</option>
        <option value="MS">Mato Grosso do Sul</option>
        <option value="MG">Minas Gerais</option>
        <option value="PA">Pará</option>
        <option value="PB">Paraíba</option>
        <option value="PR">Paraná</option>
        <option value="PE">Pernambuco</option>
        <option value="PI">Piauí</option>
        <option value="RJ">Rio de Janeiro</option>
        <option value="RN">Rio Grande do Norte</option>
        <option value="RS">Rio Grande do Sul</option>
        <option value="RO">Rôndonia</option>
        <option value="RR">Roraima</option>
        <option value="SP">São Paulo</option>
        <option value="SC">Santa Catarina</option>
        <option value="SE">Sergipe</option>
        <option value="TO">Tocantins</option>
      </select>
      
      <label for="cidade">Cidade:</label>
      <select id="cidade" name="cidade">
       <option value="">Selecione uma cidade</option>
      </select>
      
<script>
  const cidadesPorEstado = {
    AC: ["Acrelândia", "Assis Brasil", "Brasiléia", "Bujari", "Capixaba", "Cruzeiro do Sul", "Epitáciolândia", "Feijó", "Jordão", "Manoel Urbano", "Marechal Thaumaturgo", "Mâncio Lima", "Plácido de Castro", "Porto Acre", "Porto Walter", "Rio Branco", "Rodrigues Alves", "Santa Rosa do Purus", "Sena Madureira", "Senador Guiomard", "Tarauacá", "Xapuri"],
    AL: ["Água Branca", "Anadia", "Arapiraca", "Atalaia", "Barra de Santo Antônio", "Barra de São Miguel", "Batalha", "Belém", "Belo Monte", "Boca da Mata", "Branquinha", "Cacimbinhas", "Cajueiro", "Campestre", "Campo Alegre", "Campo Grande", "Canapi", "Capela", "Carneiros", "Chã Preta", "Coité do Noia", "Colônia Leopoldina", "Coqueiro Seco", "Coruripe", "Craíbas", "Delmiro Gouveia", "Dois Riachos", "Estrela de Alagoas", "Feira Grande", "Feliz Deserto", "Flexeiras", "Girau do Ponciano", "Ibateguara", "Igaci", "Igreja Nova", "Inhapi", "Jacaré dos Homens", "Jacuípe", "Japaratinga", "Jaramataia", "Jequiá da Praia", "Joaquim Gomes", "Jundiá", "Junqueiro", "Lagoa da Canoa", "Limoeiro de Anadia", "Maceió", "Major Izidoro", "Maragogi", "Maravilha", "Marechal Deodoro", "Maribondo", "Mata Grande", "Matriz de Camaragibe", "Messias", "Minador do Negrão", "Monteirópolis", "Murici", "Novo Lino", "Olho d'Água das Flores", "Olho d'Água do Casado", "Olho d'Água Grande", "Olivença", "Ouro Branco", "Palestina", "Palmeira dos Índios", "Pão de Açúcar", "Pariconha", "Passo de Camaragibe", "Paulo Jacinto", "Penedo", "Piaçabuçu", "Pilar", "Pindoba", "Piranhas", "Poço das Trincheiras", "Porto Calvo", "Porto de Pedras", "Porto Real do Colégio", "Quebrangulo", "Rio Largo", "Roteiro", "Santa Luzia do Norte", "Santana do Ipanema", "Santana do Mundaú", "São Brás", "São José da Laje", "São José da Tapera", "São Luís do Quitunde", "São Miguel dos Campos", "São Miguel dos Milagres", "São Sebastião", "Satuba", "Senador Rui Palmeira", "Tanque d'Arca", "Taquarana", "Teotônio Vilela", "Traipu", "União dos Palmares", "Viçosa"],
    AP: ["Amapá", "Calçoene", "Cutias", "Ferreira Gomes", "Itaubal", "Laranjal do Jari", "Macapá", "Mazagão", "Oiapoque", "Pedra Branca do Amapari", "Porto Grande", "Pracuúba", "Santana", "Serra do Navio", "Tartarugalzinho", "Vitória do Jari"],
    AM: ["Alvarães", "Amaturá", "Anamã", "Anori", "Apuí", "Atalaia do Norte", "Autazes", "Barcelos", "Barreirinha", "Benjamin Constant", "Beruri", "Boa Vista do Ramos", "Boca do Acre", "Borba", "Caapiranga", "Canutama", "Carauari", "Careiro", "Careiro da Várzea", "Coari", "Codajás", "Eirunepé", "Envira", "Fonte Boa", "Guajará", "Humaitá", "Ipixuna", "Iranduba", "Itacoatiara", "Itamarati", "Itapiranga", "Japurá", "Juruá", "Jutaí", "Lábrea", "Manacapuru", "Manaquiri", "Manaus", "Manicoré", "Maraã", "Maués", "Nhamundá", "Nova Olinda do Norte", "Novo Airão", "Novo Aripuanã", "Parintins", "Pauini", "Presidente Figueiredo", "Rio Preto da Eva", "Santa Isabel do Rio Negro", "Santo Antônio do Içá", "São Gabriel da Cachoeira", "São Paulo de Olivença", "São Sebastião do Uatumã", "Silves", "Tabatinga", "Tapauá", "Tefé", "Tonantins", "Uarini", "Urucará", "Urucurituba"],
    BA: ["Abaíra", "Abaré", "Acajutiba", "Adustina", "Água Fria", "Aiquara", "Alagoinhas", "Alcobaça", "Almadina", "Amargosa", "Amélia Rodrigues", "América Dourada", "Anagé", "Andaraí", "Andorinha", "Angical", "Anguera", "Antas", "Antônio Cardoso", "Antônio Gonçalves", "Aporá", "Apuarema", "Araçás", "Aracatu", "Araci", "Aramari", "Arataca", "Aratuípe", "Aurelino Leal", "Baianópolis", "Baixa Grande", "Banzaê", "Barra", "Barra da Estiva", "Barra do Choça", "Barra do Mendes", "Barra do Rocha", "Barreiras", "Barro Alto", "Barro Preto", "Barrocas", "Belmonte", "Belo Campo", "Biritinga", "Boa Nova", "Boa Vista do Tupim", "Bom Jesus da Lapa", "Bom Jesus da Serra", "Boninal", "Bonito", "Boquira", "Botuporã", "Brejões", "Brejolândia", "Brotas de Macaúbas", "Brumado", "Buerarema", "Buritirama", "Caatiba", "Cabaceiras do Paraguaçu", "Cachoeira", "Caculé", "Caém", "Caetanos", "Caetité", "Cafarnaum", "Cairu", "Caldeirão Grande", "Camacan", "Camaçari", "Camamu", "Campo Alegre de Lourdes", "Campo Formoso", "Canápolis", "Canarana", "Canavieiras", "Candeal", "Candeias", "Candiba", "Cândido Sales", "Cansanção", "Canudos", "Capela do Alto Alegre", "Capim Grosso", "Caraíbas", "Caravelas", "Cardeal da Silva", "Carinhanha", "Casa Nova", "Castro Alves", "Catolândia", "Catu", "Caturama", "Central", "Chorrochó", "Cícero Dantas", "Cipó", "Coaraci", "Cocos", "Conceição da Feira", "Conceição do Almeida", "Conceição do Coité", "Conceição do Jacuípe", "Conde", "Condeúba", "Contendas do Sincorá", "Coração de Maria", "Cordeiros", "Coribe", "Coronel João Sá", "Correntina", "Cotegipe", "Cravolândia", "Crisópolis", "Cristópolis", "Cruz das Almas", "Curaçá", "Dário Meira", "Dias d'Ávila", "Dom Basílio", "Dom Macedo Costa", "Elísio Medrado", "Encruzilhada", "Entre Rios", "Érico Cardoso", "Esplanada", "Euclides da Cunha", "Eunápolis", "Fátima", "Feira da Mata", "Feira de Santana", "Filadélfia", "Firmino Alves", "Floresta Azul", "Formosa do Rio Preto", "Gandu", "Gavião", "Gentio do Ouro", "Glória", "Gongogi", "Governador Mangabeira", "Guajeru", "Guanambi", "Guaratinga", "Heliópolis", "Iaçu", "Ibiassucê", "Ibicaraí", "Ibicoara", "Ibicuí", "Ibipeba", "Ibipitanga", "Ibiquera", "Ibirapitanga", "Ibirapuã", "Ibirataia", "Ibitiara", "Ibititá", "Ibotirama", "Ichu", "Igaporã", "Igrapiúna", "Iguaí", "Ilhéus", "Inhambupe", "Ipecaetá", "Ipiaú", "Ipirá", "Ipupiara", "Irajuba", "Iramaia", "Iraquara", "Irará", "Irecê", "Itabela", "Itaberaba", "Itabuna", "Itacaré", "Itaetê", "Itagi", "Itagibá", "Itagimirim", "Itaguaçu da Bahia", "Itaju do Colônia", "Itajuípe", "Itamaraju", "Itamari", "Itambé", "Itanagra", "Itanhém", "Itaparica", "Itapé", "Itapebi", "Itapetinga", "Itapicuru", "Itapitanga", "Itaquara", "Itarantim", "Itatim", "Itiruçu", "Itiúba", "Itororó", "Ituaçu", "Ituberá", "Iuiú", "Jaborandi", "Jacaraci", "Jacobina", "Jaguaquara", "Jaguarari", "Jaguaripe", "Jandaíra", "Jequié", "Jeremoabo", "Jiquiriçá", "Jitaúna", "João Dourado", "Juazeiro", "Jucuruçu", "Jussara", "Jussari", "Jussiape", "Lafaiete Coutinho", "Lagoa Real", "Laje", "Lajedão", "Lajedinho", "Lajedo do Tabocal", "Lamarão", "Lapão", "Lauro de Freitas", "Lençóis", "Licínio de Almeida", "Livramento de Nossa Senhora", "Luís Eduardo de Magalhães", "Macajuba", "Macarani", "Macaúbas", "Macururé", "Madre de Deus", "Maetinga", "Maiquinique", "Mairi", "Malhada de Pedras", "Malhada", "Manoel Vitorino", "Mansidão", "Maracás", "Maragogipe", "Maraú", "Marcionílio Souza", "Mascote", "Mata de São João", "Matina", "Medeiros Neto", "Miguel Calmon", "Milagres", "Mirangaba", "Mirante", "Monte Santo", "Morpará", "Morro do Chapéu", "Mortugaba", "Mucugê", "Mucuri", "Mulungu do Morro", "Mundo Novo", "Muniz Ferreira", "Muquém do São Francisco", "Muritiba", "Mutuípe", "Nazaré", "Nilo Peçanha", "Nordestina", "Nova Canaã", "Nova Fátima", "Nova Ibiá", "Nova Itarana", "Nova Redenção", "Nova Soure", "Nova Viçosa", "Novo Horizonte", "Novo Triunfo", "Olindina", "Oliveira dos Brejinhos", "Ouriçangas", "Ourolândia", "Palmas de Monte Alto", "Palmeiras", "Paramirim", "Paratinga", "Paripiranga", "Pau Brasil", "Paulo Afonso", "Pé de Serra", "Pedrão", "Pedro Alexandre", "Piatã", "Pilão Arcado", "Pindaí", "Pindobaçu", "Pintadas", "Piraí do Norte", "Piripá", "Piritiba", "Planaltino", "Planalto", "Poções", "Pojuca", "Ponto Novo", "Porto Seguro", "Potiraguá", "Prado", "Presidente Dutra", "Presidente Jânio Quadros", "Presidente Tancredo Neves", "Queimadas", "Quijingue", "Quixabeira", "Rafael Jambeiro", "Remanso", "Retirolândia", "Riachão das Neves", "Riachão do Jacuípe", "Riacho de Santana", "Ribeira do Amparo", "Ribeira do Pombal", "Ribeirão do Largo", "Rio de Contas", "Rio do Antônio", "Rio do Pires", "Rio Real", "Rodelas", "Ruy Barbosa", "Salinas da Margarida", "Salvador", "Santa Bárbara", "Santa Brígida", "Santa Cruz Cabrália", "Santa Cruz da Vitória", "Santa Inês", "Santa Luzia", "Santa Maria da Vitória", "Santa Rita de Cássia", "Santa Teresinha", "Santaluz", "Santana", "Santanópolis", "Santo Amaro", "Santo Antônio de Jesus", "Santo Estêvão", "São Desidério", "São Domingos", "São Felipe", "São Félix", "São Félix do Coribe", "São Francisco do Conde", "São Gabriel", "São Gonçalo dos Campos", "São José da Vitória", "São José do Jacuípe", "São Miguel das Matas", "São Sebastião do Passé", "Sapeaçu", "Sátiro Dias", "Saubara", "Saúde", "Seabra", "Sebastião Laranjeiras", "Senhor do Bonfim", "Sento Sé", "Serra do Ramalho", "Serra Dourada", "Serra Preta", "Serrinha", "Serrolândia", "Simões Filho", "Sítio do Mato", "Sítio do Quinto", "Sobradinho", "Souto Soares", "Tabocas do Brejo Velho", "Tanhaçu", "Tanque Novo", "Tanquinho", "Taperoá", "Tapiramutá", "Teixeira de Freitas", "Teodoro Sampaio", "Teofilândia", "Teolândia", "Terra Nova", "Tremendal", "Tucano", "Uauá", "Ubaíra", "Ubaitaba", "Ubatã", "Uibaí", "Umburanas", "Una", "Urandi", "Uruçuca", "Utinga", "Valença", "Valente", "Várzea da Roça", "Várzea do Poço", "Várzea Nova", "Varzedo", "Vera Cruz", "Vereda", "Vitória da Conquista", "Wagner", "Wanderley", "Wenceslau Guimarães", "Xique-Xique"],
    CE: ["Abaiara", "Acarape", "Acaraú", "Acopiara", "Aiuaba", "Alcântaras", "Altaneira", "Alto Santo", "Amontada", "Antonina do Norte", "Apuiarés", "Aquiraz", "Aracati", "Aracoiaba", "Ararendá", "Araripe", "Aratuba", "Arneiroz", "Assaré", "Aurora", "Baixio", "Banabuiú", "Barbalha", "Barreira", "Barro", "Barroquinha", "Baturité", "Beberibe", "Bela Cruz", "Boa Viagem", "Brejo Santo", "Camocim", "Campos Sales", "Canindé", "Capistrano", "Caridade", "Cariré", "Caririaçu", "Cariús ", "Carnaubal", "Cascavel", "Catarina", "Catunda", "Caucaia", "Cedro", "Chaval", "Choró", "Chorozinho", "Coreaú", "Crateús", "Crato", "Croatá", "Cruz", "Deputado Irapuan Pinheiro", "Ereré", "Eusébio", "Farias Brito", "Forquilha", "Fortaleza", "Fortim", "Frecheirinha", "General Sampaio", "Graça", "Granja", "Granjeiro", "Groaíras", "Guaiúba", "Guaraciaba do Norte", "Guaramiranga", "Hidrolândia", "Horizonte", "Ibaretama", "Ibiapina", "Ibicuitinga", "Icapuí", "Icó", "Iguatu", "Independência", "Ipaporanga", "Ipaumirim", "Ipu", "Ipueiras", "Iracema", "Irauçuba", "Itaiçaba", "Itaitinga", "Itapajé", "Itapipoca", "Itapiúna", "Itarema", "Itatira", "Jaguaretama", "Jaguaribara", "Jaguaribe", "Jaguaruana", "Jardim", "Jati", "Jijoca de Jericoacoara", "Juazeiro do Norte", "Jucás", "Lavras da Mangabeira", "Limoeiro do Norte", "Madalena", "Maracanaú", "Maranguape", "Marco", "Martinópole", "Massapê", "Mauriti", "Meruoca", "Milagres", "Milhã", "Miraíma", "Missão Velha", "Mombaça", "Monsenhor Tabosa", "Morada Nova", "Moraújo", "Morrinhos", "Mucambo", "Mulungu", "Nova Olinda", "Nova Russas", "Novo Oriente", "Ocara", "Orós", "Pacajus", "Pacatuba", "Pacoti", "Pacujá", "Palhano", "Palmácia", "Paracuru", "Paraipaba", "Parambu", "Paramoti", "Pedra Branca", "Penaforte", "Pentecoste", "Pereiro", "Pindoretama", "Piquet Carneiro", "Pires Ferreira", "Poranga", "Porteiras", "Potengi", "Potiretama", "Quiterianópolis", "Quixadá", "Quixelô", "Quixeramobim", "Quixeré", "Redenção", "Reriutaba", "Russas", "Saboeiro", "Salitre", "Santa Quitéria", "Santana do Acaraú", "Santana do Cariri", "São Benedito", "São Gonçalo do Amarante", "São João do Jaguaribe", "São Lúis do Curu", "Senador Pompeu", "Senador Sá", "Sobral", "Solonópole", "Tabuleiro do Norte", "Tamboril", "Tarrafas", "Tauá", "Tejuçuoca", "Tianguá", "Trairi", "Tururu", "Ubajara", "Umari", "Umirim", "Uruburetama", "Uruoca", "Varjota", "Várzea Alegre", "Viçosa do Ceará"],
    DF: ["Água Quente", "Águas Claras", "Arapoanga", "Arniqueira", "Brasília", "Brazlândia", "Candangolândia", "Ceilândia", "Cruzeiro", "Fercal", "Gama", "Guará", "Itapoã", "Lago Norte", "Lago Sul", "Núcleo Bandeirante", "Park Way", "Paranoá", "Planaltina", "Plano Piloto", "Recanto das Emas", "Riacho Fundo", "Riacho Fundo 2", "Samambaia", "Santa Maria", "São Sebastião", "SCIA", "Sobradinho", "Sobradinho 2", "Sol Nascente/Pôr do Sol", "Sudoeste/Octogonal", "Taguatinga", "Varjão", "Vicente Pires", "SIA"],
    ES: ["Afonso Cláudio", "Água Doce do Norte", "Águia Branca", "Alegre", "Alfredo Chaves", "Alto Rio Novo", "Anchieta", "Apiacá", "Aracruz", "Atílio Vivácqua", "Baixo Guandu", "Barra de São Francisco", "Boa Esperança", "Bom Jesus do Norte", "Brejetuba", "Cachoeiro de Itapemirim", "Cariacica", "Castelo", "Colatina", "Conceição da Barra", "Conceição do Castelo", "Divino de São Lourenço", "Domingos Martins", "Dores do Rio Preto", "Ecoporanga", "Fundão", "Governador Lindenberg", "Guaçuí", "Guarapari", "Ibatiba", "Ibiraçu", "Ibitirama", "Iconha", "Irupi", "Itaguaçu", "Itapemirim", "Itarana", "Iúna", "Jaguaré", "Jerônimo Monteiro", "João Neiva", "Laranja da Terra", "Linhares", "Mantenópolis", "Marataízes", "Marechal Floriano", "Marilândia", "Mimoso do Sul", "Montanha", "Mucurici", "Muniz Freire", "Muqui", "Nova Venécia", "Pancas", "Pedro Canário", "Pinheiros", "Piúma", "Ponto Belo", "Presidente Kennedy", "Rio Bananal", "Rio Novo do Sul", "Santa Leopoldina", "Santa Maria de Jetibá", "Santa Teresa", "São Domingos do Norte", "São Gabriel da Palha", "São José do Calçado", "São Mateus", "São Roque do Canaã", "Serra", "Sooretama", "Vargem Alta", "Venda Nova do Imigrante", "Viana", "Vila Pavão", "Vila Valério", "Vila Velha", "Vitória"],
    PR: ["Abatiá", "Adrianópolis", "Agudos do Sul", "Almirante Tamandaré", "Altamira do Paraná", "Alto Paraíso", "Alto Paraná", "Alto Piquiri", "Altônia", "Alvorada do Sul", "Amaporã", "Ampére", "Anahy", "Andirá", "Ângulo", "Antonina", "Antônio Olinto", "Apucarana", "Arapongas", "Arapoti", "Arapuã", "Araruna", "Araucária", "Ariranha do Ivaí", "Assaí", "Assis Chateaubriand", "Astorga", "Atalaia", "Balsa Nova", "Bandeirantes", "Barbosa Ferraz","Barra do Jacaré", "Barracão", "Bela Vista do Caroba", "Bela Vista do Paraíso", "Bituruna", "Boa Esperança", "Boa Esperança do Iguaçu", "Boa Ventura de São Roque", "Boa Vista da Aparecida", "Bocaiúva do Sul", "Bom Jesus do Sul", "Bom Sucesso", "Bom Sucesso do Sul", "Borrazópolis", "Braganey", "Brasilândia do Sul", "Cafeara", "Cafelândia", "Cafezal do Sul", "Califórnia", "Cambará", "Cambé", "Cambira", "Campina da Lagoa", "Campina do Simão", "Campina Grande do Sul", "Campo Bonito", "Campo do Tenente", "Campo Largo", "Campo Magro", "Campo Mourão", "Cândido de Abreu", "Candói", "Cantagalo", "Capanema", "Capitão Leônidas Marques", "Carambeí", "Carlópolis", "Cascavel", "Castro", "Catanduvas", "Centenário do Sul", "Cerro Azul", "Céu Azul", "Chopinzinho", "Cianorte", "Cidade Gaúcha", "Clevelândia", "Colombo", "Colorado", "Congonhinhas", "Conselheiro Mairinck", "Contenda", "Corbélia", "Cornélio Procópio", "Coronel Domingos Soares", "Coronel Vivida", "Corumbataí do Sul", "Cruz Machado", "Cruzeiro do Iguaçu", "Cruzeiro do Oeste", "Cruzeiro do Sul", "Cruzmaltina", "Curitiba", "Curiúva", "Diamante do Norte", "Diamante do Sul", "Diamante d'Oeste", "Dois Vizinhos", "Douradina", "Doutor Camargo", "Doutor Ulysses", "Enéas Marques", "Engenheiro Beltrão", "Entre Rios do Oeste", "Esperança Nova", "Espigão Alto do Iguaçu", "Farol", "Faxinal", "Fazenda Rio Grande", "Fênix", "Fernandes Pinheiro", "Figueira", "Flor da Serra do Sul", "Floraí", "Floresta", "Florestópolis", "Flórida", "Formosa do Oeste", "Foz do Iguaçu", "Foz do Jordão", "Francisco Alves", "Francisco Beltrão", "General Carneiro", "Godoy Moreira", "Goioerê","Goioxim", "Grandes Rios", "Guaíra", "Guairaçá", "Guamiranga", "Guapirama", "Guaporema", "Guaraci", "Guaraniaçu", "Guarapuava", "Guaraqueçaba", "Guaratuba", "Honório Serpa", "Ibaiti", "Ibema", "Ibiporã", "Icaraíma", "Iguaraçu", "Iguatu", "Imbaú", "Imbituva", "Inácio Martins", "Inajá", "Indianópolis", "Ipiranga", "Iporã", "Iracema do Oeste", "Irati", "Iretama", "Itaguajé", "Itaipulândia", "Itambaracá", "Itambé", "Itapejara d'Oeste", "Itaperuçu", "Itaúna do Sul", "Ivaí", "Ivaiporã", "Ivaté", "Ivatuba", "Jaboti", "Jacarezinho", "Jaguapitã", "Jaguariaíva", "Jandaia do Sul", "Janiópolis", "Japira", "Japurá", "Jardim Alegre", "Jardim Olinda", "Jataizinho", "Jesuítas", "Joaquim Távora", "Jundiaí do Sul", "Juranda", "Jussara", "Kaloré", "Lapa", "Laranjal", "Laranjeiras do Sul", "Leópolis", "Lidianópolis", "Lindoeste", "Loanda", "Lobato", "Londrina", "Luiziana", "Lunardelli", "Lupionópolis", "Mallet", "Mamborê", "Mandaguaçu", "Mandaguari", "Mandirituba", "Manfrinópolis", "Mangueirinha", "Manoel Ribas", "Marechal Cândido Rondon", "Maria Helena", "Marialva", "Marilândia do Sul", "Marilena", "Mariluz", "Maringá", "Mariópolis", "Maripá", "Marmeleiro", "Marquinho", "Marumbi", "Matelândia", "Matinhos", "Mato Rico", "Mauá da Serra", "Medianeira", "Mercedes", "Mirador", "Miraselva", "Missal", "Moreira Sales", "Morretes", "Munhoz de Mello", "Nossa Senhora das Graças", "Nova Aliança do Ivaí", "Nova América da Colina", "Nova Aurora", "Nova Cantu","Nova Esperança do Sudoeste", "Nova Esperança", "Nova Fátima", "Nova Laranjeiras", "Nova Londrina", "Nova Olímpia", "Nova Prata do Iguaçu","Nova Santa Bárbara", "Nova Santa Rosa", "Nova Tebas", "Novo Itacolomi", "Ortigueira", "Ourizona", "Ouro Verde do Oeste", "Paiçandu", "Palmas", "Palmeira", "Palmital", "Palotina", "Paraíso do Norte", "Paranacity", "Paranaguá", "Paranapoema", "Paranavaí", "Pato Bragado", "Pato Branco", "Paula Freitas", "Paulo Frontin", "Peabiru", "Perobal", "Pérola", "Pérola d'Oeste", "Piên", "Pinhais", "Pinhal de São Bento", "Pinhalão", "Pinhão", "Piraí do Sul", "Piraquara", "Pitanga", "Pitangueiras", "Planaltina do Paraná", "Planalto", "Ponta Grossa", "Pontal do Paraná", "Porecatu", "Porto Amazonas", "Porto Barreiro", "Porto Rico", "Prado Ferreira", "Pranchita", "Presidente Castelo Branco", "Primeiro de Maio", "Prudentópolis", "Quarto Centenário", "Quatiguá", "Quatro Barras", "Quatro Pontes", "Quedas do Iguaçu", "Querência do Norte", "Quinta do Sol", "Quitandinha", "Ramilândia", "Rancho Alegre", "Rancho Alegre d'Oeste", "Realeza", "Rebouças", "Renascença", "Reserva", "Reserva do Iguaçu", "Ribeirão Claro", "Ribeirão Pinhal", "Rio Azul", "Rio Bom", "Rio Bonito do Iguaçu", "Rio Branco do Ivaí", "Rio Branco do Sul", "Rio Negro", "Rolândia", "Roncador", "Rondon", "Rosário do Ivaí", "Sabáudia", "Salgado Filho", "Salto do Itararé", "Salto do Lontra", "Santa Amélia", "Santa Cecília do Pavão", "Santa Cruz de Monte Castelo", "Santa Fé", "Santa Inês", "Santa Isabel do Ivaí", "Santa Izabel do Oeste", "Santa Lúcia", "Santa Maria do Oeste", "Santa Mariana", "Santa Mônica", "Santa Tereza do Oeste", "Santa Terezinha de Itaipu", "Santana do Itararé", "Santo Antônio da Platina", "Santo Antônio do Caiuá", "Santo Antônio do Paraíso", "Santo  Antônio do Sudoeste", "Santo Inácio", "São Carlos do Ivaí", "São Jerônimo da Serra", "São João", "São João do Caiuá", "São João do Ivaí", "São João do Triunfo", "São Jorge do Ivaí", "São Jorge do Patrocínio", "São Jorge d'Oeste", "São José da Boa Vista", "São José das Palmeiras","São José dos Pinhais", "São Manoel do Paraná", "São Mateus do Sul", "São Miguel do Iguaçu", "São Pedro do Iguaçu", "São Pedro do Ivaí", "São Pedro do Paraná", "São Sebastião da Amoreira", "São Tomé", "Sapopema", "Sarandi", "Saudade do Iguaçu", "Sengés", "Serranópolis do Iguaçu", "Sertaneja", "Sertanópolis", "Siqueira Campos", "Sulina", "Tamarana", "Tamboara", "Tapejara", "Tapira", "Teixeira Soares", "Telêmaco Borba", "Terra Boa", "Terra Rica", "Terra Roxa", "Tibagi", "Tijucas do Sul", "Toledo", "Tomazina", "Três Barras do Paraná", "Tunas do Paraná", "Tuneiras do Oeste", "Tupãssi", "Turvo", "Ubiratã", "Umuarama", "União da Vitória", "Uniflor", "Uraí", "Ventania", "Vera Cruz do Oeste", "Verê", "Virmond", "Vitorino", "Wenceslau Braz", "Xambrê"],
    RJ: ["Angra dos Reis", "Aperibé", "Araruama", "Areal", "Armação dos Búzios", "Arraial do Cabo", "Barra do Piraí", "Barra Mansa", "Belford Roxo", "Bom Jardim", "Bom Jesus do Itabapoama", "Cabo Frio", "Cachoeiras de Macacu", "Cambuci", "Campos dos Goytacazes", "Cantagalo", "Carapebus", "Cardoso Moreira", "Carmo", "Casimiro de Abreu", "Comendador Levy Gasparian", "Conceição de Macabu", "Cordeiro", "Duas Barras", "Duque de Caxias", "Engenheiro Paulo de Frontin", "Guapimirim", "Iguaba Grande", "Itaboraí", "Itaguaí", "Italva", "Itaocara", "Itaperuna", "Itatiaia", "Japeri", "Laje do Muriaé", "Macaé", "Macuco", "Magé", "Mangaratiba", "Maricá", "Mendes", "Mesquita", "Miguel Pereira", "Miracema", "Natividade", "Nilópolis", "Niterói", "Nova Friburgo", "Nova Iguaçu", "Paracambi", "Paraíba do Sul", "Paraty", "Paty do Alferes", "Petrópolis", "Pinheiral", "Piraí", "Porciúncula", "Porto Real", "Quatis", "Queimados", "Quissamã", "Resende", "Rio Bonito", "Rio Claro", "Rio das Flores", "Rio das Ostras", "Rio de Janeiro", "Santa Maria Madalena", "Santo Antônio de Pádua", "São Fidélis", "São Francisco de Itabapoana", "São Gonçalo", "São João da Barra", "São João de Meriti", "São José de Ubá", "São José do Vale do Rio Preto", " São Pedro da Aldeia", "São Sebastião do Alto", "Sapucaia", "Saquarema", "Seropédica", "Silva Jardim", "Sumidouro", "Tanguá", "Teresópolis", "Trajano de Moraes", "Três Rios", "Valença", "Varre-Sal", "Vassouras", "Volta Redonda"],
    SC:["Abdon Batista", "Abelardo Luz", "Agrolândia", "Agronômica", "Água Doce", "Águas de Chapecó", "Águas Frias", "Águas Mornas", "Alfredo Wagner", "Alto Bela Vista", "Anchieta", "Angelina", "Anita Garibaldi", "Anitápolis", "Antônio Carlos", "Apiúna", "Arabutã", "Araquari", "Araranguá", "Armazém", "Arroio Trinta", "Arvoredo", "Ascurra", "Atalanta", "Aurora", "Balneário Arroio do Silva", "Balneário Barra do Sul", "Balneário Camboriú", "Balneário Gaivota", "Balneário Piçarras", "Balneário Rincão", "Bandeirante", "Barra Bonita", "Barra Velha", "Bela Vista do Toldo", "Belmonte", "Benedito Novo", "Biguaçu", "Blumenau", "Bocaina do Sul", "Bom Jardim da Serra", "Bom Jesus", "Bom Jesus do Oeste", "Bom Retiro", "Bombinhas", "Botuverá", "Braço do Norte", "Braço do Trombudo", "Brunópolis", "Brusque", "Caçador", "Caibi", "Calmon", "Camboriú", "Campo Alegre", "Campo Belo Sul", "Campo Erê", "Campos Novos", "Canelinha", "Canoinhas", "Capão Alto", "Capinzal", "Capivari de Baixo", "Catanduvas", "Caxambu do Sul", "Celso Ramos", "Cerro Negro", "Chapadão do Lageado", "Chapecó", "Cocal do Sul", "Concórdia", "Cordilheira Alta", "Coronel Freitas", "Coronel Martins", "Correia Pinto", "Corupá", "Criciúma", "Cunha Porã", "Cunhataí", "Curitibanos", "Descanso", "Dionísio Cerqueira", "Dona Emma", "Doutor Pedrinho", "Entre Rios", "Ermo", "Erval Velho", "Faxinal dos Guedes", "Flor do Sertão", "Florianópolis", "Formosa do Sul", "Forquilhinha", "Fraiburgo", "Frei Rogério", "Galvão", "Garopaba", "Garuva", "Gaspar", "Governador Celso Ramos", "Grão-Pará", "Gravatal", "Guabiruba", "Guaraciaba", "Guaramirim", "Guarujá do Sul", "Guatambu", "Herval d'Oeste", "Ibiam", "Ibicaré", "Ibirama", "Içara", "Ilhota", "Imaruí", "Imbituba", "Imbuia", "Indaial", "Iomerê", "Ipira", "Iporã do Oeste", "Ipuaçu", "Ipumirim", "Iraceminha", "Irani", "Irati", "Ireneópolis", "Itá", "Itaiópolis", "Itajaí", "Itapema", "Itapiranga", "Itapoá", "Ituporanga", "Jaborá", "Jacinto Machado", "Jaguaruna", "Jaraguá do Sul", "Jardinópolis", "Joaçacaba", "Joinville", "José Boiteux", "Jupiá", "Lacerdópolis", "Lages", "Laguna", "Lajeado Grande", "Laurentino", "Lauro Müller", "Lebon Régis", "Leoberto Real", "Lindóia do Sul", "Lontras", "Luiz Alves", "Luzerna", "Macieira", "Mafra", "Major Gercino", "Major Vieira", "Maracajá", "Maravilha", "Marema", "Massaranduba", "Matos Costa", "Meleiro", "Mirim Doce", "Modelo", "Mondaí", "Monte Carlo", "Morro da Fumaça", "Morro Grande", "Navegantes", "Nova Erechim", "Nova Itaberaba", "Nova Trento", "Nova Veneza", "Novo Horizonte", "Orleans", "Otacílio Costa", "Ouro", "Ouro Verde", "Paial", "Painel", "Palhoça", "Palma Sola", "Palmeira", "Palmitos", "Papanduva", "Paraíso", "Passo de Torres", "Passos Maia", "Paulo Lopes", "Pedras Grandes", "Penha", "Peritiba", "Pescaria Brava", "Petrolândia", "Pinhalzinho", "Pinheiro Preto", "Piratuba", "Planalto Alegre", "Pomerode", "Ponte Alta", "Ponte Alta do Norte", "Ponte Serrada", "Porto Belo", "Porto União", "Pouso Redondo", "Praia Grande", "Presidente Castello Branco", "Presidente Getúlio", "Presidente Nereu", "Princesa", "Quilombo", "Rancho Queimado", "Rio das Antas", "Rio do Campo", "Rio do Oeste","Rio do Sul", "Rio dos Cedros", "Rio Fortuna", "Rio Negrinho", "Rio Rufino", "Riqueza", "Rodeio", "Romelândia", "Salete", "Saltinho", "Salto Veloso", "Sangão", "Santa Cecília", "Santa Helena", "Santa Rosa de Lima", "Santa Rosa do Sul", "Santa Teresinha", "Santa Teresinha do Progresso", "Santiago do Sul", "Santo Amaro da Imperatriz", "São Bento do Sul", "São Bernadino", "São Bonifácio", "São Carlos", "São Cristovão do Sul", "São Domingos", "São Francisco do Sul", "São João Batista", "São João do Itaperiú", "São João do Oeste", "São João do Sul", "São Joaquim", "São José", "São José do Cedro", "São José do Cerrito", "São Lourenço do Oeste", "São Ludgero", "São Martinho", "São Miguel da Boa Vista", "São Miguel do Oeste", "São Pedro de Alcântara", "Saudades", "Schroeder", "Seara", "Serra Alta", "Siderópolis", "Sombrio", "Sul Brasil", "Taió", "Tangará", "Tigrinhos", "Tijucas", "Timbé do Sul", "Timbó", "Timbó Grande", "Três Barras", "Treviso", "Treze de Maio", "Treze Tílias", "Trombudo Central", "Tubarão", "Tunápolis", "Treviso", "União do Oeste", "Urubici", "Urupema", "Urussanga", "Vargeão", "Vargem", "Vargem Bonita", "Vidal Ramos", "Videira", "Vitor Meireles", "Witmarsum", "Xanxerê", "Xavantina", "Xaxim", "Zortéa"],
    SP: ["Adamantina", "Adolfo", "Aguaí", "Águas da Prata", "Águas de Lindóia", "Águas de Santa Bárbara", "Águas de São Pedro", "Agudos", "Alambari", "Alfredo Marcondes", "Altair", "Altinópolis", "Alto Alegre", "Alumínio", "Álvares Florence", "Álvares Machado", "Álvaro de Carvalho", "Alvinlândia", "Americana", "Américo Brasiliense", "Américo de Campos", "Amparo", "Analândia", "Andradina", "Angatuba", "Anhembi", "Anhumas", "Aparecida", "Aparecida D'Oeste", "Apiaí", "Araçariguama", "Araçatuba", "Araçoiaba da Serra", "Aramina", "Arandu", "Arapeí", "Araraquara", "Araras", "Arco-Íris", "Arealva", "Areias", "Areiópolis", "Ariranha", "Artur Nogueira", "Arujá", "Aspásia", "Assis", "Atibaia", "Auriflama", "Avaí", "Avanhandava", "Avaré", "Bady Bassitt", "Balbinos", "Bálsamo", "Bananal", "Barão de Antonina", "Barbosa", "Bariri", "Barra Bonita", "Barra do Chapéu", "Barra do Turvo", "Barretos", "Barrinha", "Barueri", "Bastos", "Batatais", "Bauru", "Bebedouro", "Bento de Abreu", "Bernardino de Campos", "Bertioga", "Bilac", "Birigui", "Biritiba Mirim", "Boa Esperança do Sul", "Bocaina", "Bofete", "Boituva", "Bom Jesus dos Perdões", "Bom Sucesso de Itararé", "Borá", "Boraceia", "Borborema", "Borebi", "Botucatu", "Bragança Paulista", "Braúna", "Brejo Alegre", "Brodowski", "Brotas", "Buri", "Buritama", "Buritizal", "Cabrália Paulista", "Cabreúva", "Caçapava", "Cachoeira Paulista", "Caconde", "Cafelândia", "Caiabu", "Caieiras", "Caiuá", "Cajamar", "Cajati", "Cajobi", "Cajuru", "Campina do Monte Alegre", "Campinas", "Campo Limpo Paulista", "Campos do Jordão", "Campos Novos Paulista", "Cananéia", "Canas", "Cândido Mota", "Cândido Rodrigues", "Canitar", "Capão Bonito", "Capela do Alto", "Capivari", "Caraguatatuba", "Carapicuíba", "Cardoso", "Casa Branca", "Cássia dos Coqueiros", "Castilho", "Catanduva", "Catiguá", "Cedral", "Cerqueira César", "Cerquilho", "Cesário Lange", "Charqueada", "Chavantes", "Clementina", "Colina", "Colômbia", "Conchal", "Conchas", "Cordeirópolis", "Coroados", "Coronel Macedo", "Corumbataí", "Cosmópolis", "Cosmorama", "Cotia", "Cravinhos", "Cristais Paulistas", "Cruzália", "Cruzeiro", "Cubatão", "Cunha", "Descalvado", "Diadema", "Dirce Reis", "Divinolândia", "Dobrada", "Dois Córregos", "Dolcinópolis", "Dourado", "Dracena", "Duartina", "Dumont", "Echaporã", "Eldorado", "Elias Fausto", "Elisiário", "Embaúba", "Embu das Artes", "Embu-Guaçu", "Emilianópolis", "Engenheiro Coelho", "Espírito Santo do Pinhal", "Espírito Santo do Turvo", "Estiva Gerbi", "Estrela do Norte", "Estrela D'Oeste", "Euclides da Cunha Paulista", "Fartura", "Fernando Prestes", "Fernandópolis", "Fernão", "Ferraz de Vasconcelos", "Flora Rica", "Floreal", "Flórida Paulista", "Florínea", "Franca", "Francisco Morato", "Franco da Rocha", "Gabriel Monteiro", "Gália", "Garça", "Gastão Vidigal", "Gavião Peixoto", "General Salgado", "Getulina", "Glicério", "Guaiçara", "Guaimbê", "Guaíra", "Guapiaçu", "Guapiara", "Guará", "Guaraçaí", "Guaraci", "Guarani d'Oeste", "Guarantã", "Guararapes", "Guararema", "Guaratinguetá", "Guareí", "Guariba", "Guarujá", "Guarulhos", "Guatapará", "Guzolândia", "Herculândia", "Holambra", "Hortolândia", "Iacanga", "Iacri", "Iaras", "Ibaté", "Ibirá", "Ibirarema", "Ibitinga", "Ibiúna", "Icém", "Iepê", "Igaraçu do tietê", "Igarapava", "Igaratá", "Iguape", "Ilha Comprida", "Ilha Solteira", "Ilhabela", "Indaiatuba", "Indiana", "Indiaporã", "Inúbia Paulista", "Ipaussu", "Iperó", "Ipeúna", "Ipiguá", "Iporanga", "Ipuã", "Iracemápolis", "Irapuã", "Irapuru", "Itaberá", "Itaí", "Itajobi", "Itaju", "Itanhaém", "Itaoca", "Itapecerica da Serra", "Itapetininga", "Itapeva", "Itapevi", "Itapira", "Itapirapuã Paulista", "Itápolis", "Itaporanga", "Itapuí", "Itapura", "Itaquaquecetuba", "Itararé", "Itariri", "Itatiba", "Itatinga", "Itirapina", "Itirapuã", "Itobi", "Itu", "Itupeva", "Ituverava", "Jaborandi", "Jaboticabal", "Jacareí", "Jaci", "Jacupiranga", "Jaguariúna", "Jales", "Jambeiro", "Jandira", "Jardinópolis", "Jarinu", "Jaú", "Jeriquara", "Joanópolis", "João Ramalho", "José Bonifácio", "Júlio Mesquita", "Jumirim", "Jundiaí", "Junqueirópolis", "Juquiá", "Juquitiba", "Lagoinha", "Laranjal Paulista", "Lavínia", "Lavrinhas", "Leme", "Lençóis Paulista", "Limeira", "Lindoia", "Lins", "Lorena", "Lourdes", "Louveira", "Lucélia", "Lucianópolis", "Luiz Antônio", "Luiziânia", "Lupércio", "Lutécia", "Macatuba", "Macaubal", "Macedônia", "Magda", "Mairinque", "Mairiporã", "Manduri", "Marabá Paulista", "Maracaí", "Marapoama", "Mariápolis", "Marília", "Marinópolis", "Martinópolis", "Matão", "Mauá", "Mendonça", "Meridiano", "Mesópolis", "Miguelópolis", "Mineiros de Tietê", "Mira Estrela", "Miracatu", "Mirandópolis", "Mirante do Paranapanema", "Mirassol", "Mirassolândia", "Mococa", "Mogi das Cruzes", "Mogi Guaçu", "Mogi Mirim", "Mombuca", "Monções", "Mongaguá", "Monte Alegre do Sul", "Monte Alto", "Monte Aprazível", "Monte Azul Paulista", "Monte Castelo", "Monte Mor", "Monteiro Lobato", "Morro Agudo", "Morungaba", "Motuca", "Murutinga do Sul", "Nantes", "Narandiba", "Natividade da Serra", "Nazaré Paulista", "Neves Paulista", "Nhandeara", "Nipoã", "Nova Aliança", "Nova Campina", "Nova Canaã Paulista", "Nova Castilho", "Nova Europa", "Nova Granada", "Nova Guataporanga", "Nova Indepêndencia", "Nova Luzitânia", "Nova Odessa", "Novais", "Novo Horizonte", "Nuporanga", "Ocauçu", "Óleo", "Olímpia", "Onda Verde", "Oriente", "Orindiúva", "Orlândia", "Osasco", "Oscar Bressane", "Osvaldo Cruz", "Ourinhos", "Ouro Verde", "Ouroeste", "Pacaembu", "Palestina", "Palmares Paulista", "Palmeira d'Oeste", "Palmital", "Panorama", "Paraguaçu Paulista", "Paraibuna", "Paraíso", "Paranapanema", "Paranapuã", "Parapuã", "Pardinho", "Pariquera-Açu", "Parisi", "Patrocínio Paulista", "Paulicéia", "Paulínia", "Paulistânia", "Paulo de Faria", "Pederneiras", "Pedra Bela", "Pedranópolis", "Pedregulho", "Pedreira", "Pedrinhas Paulistas", "Pedro de Toledo", "Penápolis", "Pereira Barreto", "Pereiras", "Peruíbe", "Piacatu", "Piedade", "Pilar do Sul", "Pindamonhangaba", "Pindorama", "Pinhalzinho", "Piquerobi", "Piquete", "Piracaia", "Piracicaba", "Piraju", "Pirajuí", "Pirangi", "Pirapora do Bom Jesus", "Pirapozinho", "Pirassununga", "Piratininga", "Pitangueiras", "Planalto", "Platina", "Poá", "Poloni", "Pompeia", "Pongaí", "Pontal", "Pontalinda", "Pontes Gestal", "Populina", "Porangaba", "Porto Feliz", "Porto Ferreira", "Potim", "Potirendaba", "Pracinha", "Pradópolis", "Praia Grande", "Pratânia", "Presidente Alves", "Presidente Bernardes", "Presidente Epitácio", "Presidente Prudente", "Presidente Venceslau", "Promissão", "Quadra", "Quatá", "Queiroz", "Queluz", "Quintana", "Rafard", "Rancharia", "Redenção da Serra", "Regente Feijó", "Reginópolis", "Registro", "Restinga", "Ribeira", "Ribeirão Bonito", "Ribeirão Branco", "Ribeirão Corrente", "Ribeirão do Sul", "Ribeirão dos Índios", "Ribeirão Grande", "Ribeirão Pires", "Ribeirão Preto", "Rifaina", "Rincão", "Rinópolis", "Rio Claro", "Rio das Pedras", "Rio Grande da Serra", "Riolândia", "Riversul", "Rosana", "Roseira", "Rubiácea", "Rubinéia", "Sabino", "Sagres", "Sales", "Sales Oliveira", "Salesópolis", "Salmourão", "Saltinho", "Salto", "Salto de Pirapora", "Salto Grande", "Sandovalina", "Santa Adélia", "Santa Albertina", "Santa Bárbara d'Oeste", "Santa Branca", "Santa Clara d'Oeste", "Santa Cruz da Conceição", "Santa Cruz da Esperança", "Santa Cruz das Palmeiras", "Santa Cruz do Rio Pardo", "Santa Ernestina", "Santa Fé do Sul", "Santa Gertrudes", "Santa Isabel", "Santa Lúcia", "Santa Maria da Serra", "Santa Mercedes", "Santa Rita do Passa Quatro", "Santa Rita d'Oeste", "Santa Rosa de Viterbo", "Santa Salete", "Santana da Ponte Pensa", "Santana de Parnaíba", "Santo Anastácio", "Santo André", "Santo Antônio da Alegria", "Santo Antônio de Posse", "Santo Antônio do Aracanguá", "Santo Antônio do Jardim", "Santo Antônio do Pinhal", "Santo Expedito", "Santópolis do Aguapeí", "Santos", "São Bento do Sapucaí", "São Bernardo do Campo", "São Caetano do Sul", "São Carlos", "São Francisco", "São João da Boa Vista", "São João das Duas Pontes", "São João de Iracema", "São João do Pau-d'Alho", "São Joaquim da Barra", "São José da Bela Vista", "São José do Barreiro", "São José do Rio Pardo", "São José do Rio Preto", "São José dos Campos", "São Lourenço da Serra", "São Luiz do Paraitinga", "São Manuel", "São Miguel Arcanjo", "São Paulo", "São Pedro", "São Pedro do Turvo", "São Roque", "São Sebastião", "São Sebastião da Grama", "São Simão", "São Vicente", "Sarapuí", "Sarutaiá", "Sebastianópolis do Sul", "Serra Azul", "Serra Negra", "Serrana", "Sertãozinho", "Sete Barras", "Severínia", "Silveiras", "Socorro", "Sorocaba", "Sud Mennucci", "Sumaré", "Suzanápolis", "Suzano", "Tabapuã", "Tabatinga", "Taboão da Serra", "Taciba", "Taguaí", "Taiaçu", "Taiúva", "Tambaú", "Tanabi", "Tapiraí", "Tapiratiba", "Taquaral", "Taquaritinga", "Taquarituba", "Taquarivaí", "Tarabai", "Tarumã", "Tatuí", "Taubaté", "Tejupá", "Teodoro Sampaio", "Terra Roxa",  "Tietê", "Timburi", "Torre de Pedra", "Torrinha", "Trabiju", "Tremembé", "Três Fronteiras", "Tuiuti", "Tupã", "Tupi Paulista", "Turiúba", "Turmalina", "Ubarana", "Ubatuba", "Ubirajara", "Uchoa", "União Paulista", "Urânia", "Uru", "Urupês", "Valentim Gentil", "Valinhos", "Valparaíso", "Vargem", "Vargem Grande do Sul", "Vargem Grande Paulista", "Várzea Paulista", "Vera Cruz", "Vinhedo", "Viradouro", "Vista Alegre do Alto", "Vitória Brasil", "Votorantim", "Votuporanga", "Zacarias"],
    MG: ["Belo Horizonte", "Uberlândia", "Juiz de Fora", "Contagem", "Santa Cruz de Minas"],
    MS: ["Água Clara", "Alcinópolis", "Amambai", "Anastácio", "Anaurilândia", "Angélica", "Antônio João", "Aparecida do Taboado", "Aquidauana", "Aral Moreira", "Bandeirantes", "Bataguassu", "Batayporã", "Bela Vista", "Bodoquena", "Bonito", "Brasilândia", "Caarapó", "Camapuã", "Campo Grande", "Caracol", "Cassilândia", "Chapadão do Sul", "Corguinho", "Coronel Sapucaia", "Corumbá", "Costa Rica", "Coxim", "Deodápolis", "Dois Irmãos do Buriti", "Douradina", "Dourados", "Eldorado", "Fátima do Sul", "Figueirão", "Glória de Dourados", "Guia Lopes da Laguna", "Iguatemi", "Inocência", "Itaporã", "Itaquiraí", "Ivinhema", "Japorã", "Jaraguari", "Jardim", "Jateí", "Juti", "Ladário", "Laguna Carapã", "Maracaju", "Miranda", "Mundo Novo", "Naviraí", "Nioaque", "Nova Alvorada do Sul", "Nova Andradina", "Novo Horizonte do Sul", "Paraíso das Águas", "Paranaíba", "Paranhos", "Pedro Gomes", "Ponta Porã", "Porto Murtinho", "Ribas do Rio Pardo", "Rio Brilhante", "Rio Negro", "Rio Verde de Mato Grosso", "Rochedo", "Santa Rita do Pardo", "São Gabriel do Oeste", "Selvíria", "Sete Quedas", "Sidrolândia", "Sonora", "Tacuru", "Taquarussu", "Terenos", "Três Lagoas", "Vicentina"],
    MT: ["Acorizal", "Água Boa", "Alta Floresta", "Alto Araguaia", "Alto Boa Vista", "Alto Garças", "Alto Paraguai", "Alto Taquari", "Apiacás", "Araguaiana", "Araguainha", "Araputanga", "Arenápolis", "Aripuanã", "Barão de Melgaço", "Barra do Bugres", "Barra do Garças", "Boa Esperança do Norte", "Bom Jesus do Araguaia", "Brasnorte", "Cáceres", "Campinápolis", "Campo Novo do Parecis", "Campo Verde", "Campos de Júlio", "Canabrava do Norte", "Canarana", "Carlinda", "Castanheira", "Chapada dos Guimarães", "Cláudia", "Cocalinho", "Colíder", "Colniza", "Comodoro", "Confresa", "Conquista d'Oeste", "Cotriguaçu", "Cuiabá", "Curvelândia", "Denise", "Diamantino", "Dom Aquino", "Feliz Natal", "Figueirópolis d'Oeste", "Gaúcha do Norte", "General Carneiro", "Glória d'Oeste", "Guarantã do Norte", "Guiratinga", "Indiavaí", "Ipiranga do Norte", "Itanhangá", "Itaúba", "Itiquira", "Jaciara", "Jangada", "Jauru", "Juara", "Juína", "Juruena", "Juscimeira", "Lambari d'Oeste", "Lucas do Rio Verde", "Luciara", "Marcelândia", "Matupá", "Mirassol d'Oeste", "Nobres", "Nortelândia", "Nossa Senhora do Livramento", "Nova Bandeirantes", "Nova Brasilândia", "Nova Canaã do Norte", "Nova Guarita", "Nova Lacerda", "Nova Marilândia", "Nova Maringá", "Nova Monte Verde", "Nova Mutum", "Nova Nazaré", "Nova Olímpia", "Nova Santa Helena", "Nova Ubiratã", "Nova Xavantina", "Novo Horizonte do Norte", "Novo Mundo", "Novo Santo Antônio", "Novo São Joaquim", "Paranaíta", "Paranatinga", "Pedra Preta", "Peixoto de Azevedo", "Planalto da Serra", "Poconé", "Pontal do Araguaia", "Ponte Branca", "Pontes e Lacerda", "Porto Alegre do Norte", "Porto dos Gaúchos", "Porto Esperidião", "Porto Estrela", "Poxoréu", "Primavera do Leste", "Querência", "Reserva do Cabaçal", "Ribeirão Cascalheira", "Ribeirãozinho", "Rio Branco", "Rondolândia", "Rondonópolis", "Rosário Oeste", "Salto do Céu", "Santa Carmem", "Santa Cruz do Xingu", "Santa Rita do Trivelato", "Santa Terezinha", "Santo Afonso", "Santo Antônio do Leste", "Santo Antônio de Leverger", "São Félix do Araguaia", "São José do Povo", "São José do Rio Claro", "São José do Xingu", "São José dos Quatro Marcos", "São Pedro da Cipa", "Sapezal", "Serra Nova Dourada", "Sinop", "Sorriso", "Tabaporã", "Tangará da Serra", "Tapurah", "Terra Nova do Norte", "Tesouro", "Torixoréu", "União do Sul", "Vale de São Domingos", "Várzea Grande", "Vera", "Vila Bela da Santíssima Trindade", "Vila Rica"],
    RN: ["Acari", "Afonso Bezerra", "Água Nova", "Alexandria", "Almino Afonso", "Alto do Rodrigues", "Angicos", "Antônio Martins", "Apodi", "Areia Branca", "Arez", "Assu", "Baía Formosa", "Baraúna", "Barcelona", "Bento Fernandes", "Boa Saúde", "Bodó", "Bom Jesus", "Brejinho", "Caiçara do Norte", "Caiçara do Rio do Vento", "Caicó", "Campo Grande", "Campo Redondo", "Canguaretama", "Caraúbas", "Carnaúba dos Dantas", "Carnaubais", "Ceará-Mirim", "Cerro Corá", "Coronel Ezequiel", "Coronel João Pessoa", "Cruzeta", "Currais Novos", "Doutor Severiano", "Encanto", "Equador", "Espírito Santo", "Extremoz", "Felipe Guerra", "Fernando Pedroza", "Florânia", "Francisco Dantas", "Frutuoso Gomes", "Galinhos", "Goianinha", "Governador Dix-Sept Rosado", "Grossos", "Guamaré", "Ielmo Marinho", "Ipanguaçu", "Ipueira", "Itajá", "Itaú", "Jaçanã", "Jandaíra", "Janduís", "Japi", "Jardim de Angicos", "Jardim de Piranhas", "Jardim de Seridó", "João Câmara", "João Dias", "José da Penha", "Jucurutu", "Jundiá", "Lagoa d'Anta", "Lagoa de Pedras", "Lagoa de Velhos", "Lagoa Nova", "Lagoa Salgada", "Lajes", "Lajes Pintadas", "Lucrécia", "Luís Gomes", "Macaíba", "Macau", "Major Sales", "Marcelino Vieira", "Martins", "Maxaranguape", "Messias Targino", "Montanhas", "Monte Alegre", "Monte das Gameleiras", "Mossoró", "Natal", "Nísia Floresta", "Nova Cruz", "Olho-d'Água do Borges", "Ouro Branco", "Paraná", "Paraú", "Parazinho", "Parelhas", "Parnamirim", "Passa-e-Fica", "Patu", "Pau dos Ferros", "Pedra Grande", "Pedra Preta", "Pedro Avelino", "Pedro Velho", "Pendências", "Pilões", "Poço Branco", "Portalegre", "Porto do Mangue", "Pureza", "Rafael Fernandes", "Rafael Godeiro", "Riacho da Cruz", "Riacho de Santana", "Riachuelo", "Rio do Fogo", "Rodolfo Fernandes", "Ruy Barbosa", "Santa Cruz", "Santa Maria", "Santana do Matos", "Santana do Seridó", "Santo Antônio", "São Bento do Norte", "São Bento do Trairi", "São Fernando", "São Francisco do Oeste", "São Gonçalo do Amarante", "São João do Sabugi", "São José do Mipibu", "São José do Campestre", "São José do Seridó", "São Miguel", "São Miguel do Gostoso", "São Paulo do Potengi", "São Pedro", "São Rafael", "São Tomé", "São Vincente", "Senador Elói de Souza", "Senador Georgino Avelino", "Serra Caiada", "Serra de São Bento", "Serra do Mel", "Serra Negra do Norte", "Serrinha", "Serrinha dos Pintos", "Severiano Melo", "Sítio Novo", "Taboleiro Grande", "Taipu", "Tangará", "Tenente Ananias", "Tenente Laurentino Cruz", "Tibau", "Tibau do Sul", "Timbaúba dos Batistas", "Touros", "Triunfo Potiguar", "Umarizal", "Upanema", "Várzea", "Venha-Ver", "Vera Cruz", "Viçosa", "Vila Flor"],
    RO: ["Alta Floresta d'Oeste", "Alto Alegre dos Parecis", "Alto Paraíso", "Alvorada d'Oeste", "Ariquemes", "Buritis", "Cabixi", "Cacaulândia", "Cacoal", "Campo Novo de Rondônia", "Candeias do Jamari", "Castanheiras", "Cerejeiras", "Chupinguaia", "Colorado do Oeste", "Corumbiara", "Costa Marques", "Cujubim", "Espigão d'Oeste", "Governador Jorge Teixeira", "Guajará-Mirim", "Itapuã do Oeste", "Jaru", "Ji-Paraná", "Machadinho d'Oeste", "Ministro Andreazza", "Mirante da  Serra", "Monte Negro", "Nova Brasilândia d'Oeste", "Nova Mamoré", "Nova União", "Novo Horizonte do Oeste", "Ouro Preto do Oeste", "Parecis", "Pimenta Bueno", "Pimenteiras do Oeste", "Porto Velho", "Presidente Médici", "Primavera de Rondônia", "Rio Crespo", "Rolim de Moura", "Santa Luzia d'Oeste", "São Felipe d'Oeste", "São Francisco do Guaporé", "São Miguel do Guaporé", "Seringueiras", "Teixeirópolis", "Theobroma", "Urupá", "Vale do Anari", "Vale do Paraíso", "Vilhena"],
    RR: ["Amajari", "Alto Alegre", "Boa Vista", "Bonfim", "Cantá", "Caracaraí", "Caroebe", "Iracema", "Mucajaí", "Normandia", "Pacaraima", "Rorainópolis", "São João da Baliza", "São Luíz", "Uiramatã"],
    RS: ["Aceguá", "Água Santa", "Agudo", "Ajuricaba", "Alecrim", "Alegrete", "Alegria", "Almirante Tamandaré do Sul", "Alpestre", "Alto Alegre", "Alto Feliz", "Alvorada", "Amaral Ferrador", "Ametista do Sul", "André da Rocha", "Anta Gorda", "Antônio Prado", " Arambaré", "Araricá", "Aratiba", "Arroio do Meio", "Arroio do Padre", "Arroio do Sal", "Arroio do Tigre", "Arroio dos Ratos", "Arroio Grande", "Arvorezinha", "Augusto Pestana", "Áurea", "Bagé", "Balneário Pinhal", "Barão", "Barão de Cotegipe", "Barão do Triunfo", "Barra do Guarita", "Barra do Quaraí", "Barra do Ribeiro", "Barra do Rio Azul", "Barra Funda","Barracão", "Barros Cassal", "Benjamin Constant do Sul", "Bento Gonçalves", "Boa Vista das Missões", "Boa Vista do Buricá", "Boa Vista do Cadeado", "Boa Vista do Incra", "Boa Vista do Sul", "Bom Jesus", "Bom Princípio", "Bom Progresso", "Bom Retiro do Sul", "Boqueirão do Leão", "Bossoroca", "Bozano", "Braga", "Brochier", "Butiá", "Caçapava do Sul", "Cacequi", "Cachoeira do Sul", "Cachoeirinha", "Cacique Doble", "Caibaté", "Caiçara", "Carmaquã", "Camargo", "Cambará do Sul", "Campestre da Serra", "Campina das Missões", "Campinas do Sul", "Campo Bom", "Campo Novo", "Campos Borges", "Candelária", "Cândido Godói", "Candiota", "Canela", "Canguçu", "Canoas", "Canudos do Vale", "Capão Bonito do Sul", "Capão da Canoa", "Capão do Cipó", "Capão do Leão", "Capela de Santana", "Capitão", "Capivari do Sul", "Caraá", "Carazinho", "Carlos Barbosa", "Carlos Gomes", "Casca", "Caseiros", "Catuípe", "Caxias do Sul", "Centenário", "Cerrito", "Cerro Branco", "Cerro Grande do Sul", "Cerro Grande", "Cerro Largo", "Chapada", "Charqueadas", "Charrua", "Chiapetta", "Chuí", "Chuvisca", "Cidreira", "Ciríaco", "Colinas", "Colorado", "Condor", "Constantina", "Coqueiro Baixo", "Coqueiros do Sul", "Coronel Barros", "Coronel Bicaco", "Coronel Pilar", "Cotiporã", "Coxilha", "Crissiumal", "Cristal", "Cristal do Sul", "Cruz Alta", "Cruzaltense", "Cruzeiro do Sul", "David Canabarro", "Derrubadas", "Dezesseis de Novembro", "Dilermando de Aguiar", "Dois Irmãos das Missões", "Dois Irmãos", "Dois Lajeados", "Dom Feliciano", "Dom Pedrito", "Dom Pedro de Alcântara", "Dona Francisca", "Doutor Maurício Cardoso", "Doutor Ricardo", "Eldorado do Sul", "Encantado", "Encruzilhada do Sul", "Engenho Velho", "Entre Rios do Sul", "Entre-Ijuís", "Erebango", "Erechim", "Ernestina", "Erval Grande", "Erval Seco", "Esmeralda", "Esperança do Sul", "Espumoso", "Estação", "Estância Velha", "Esteio", "Estrela", "Estrela Velha", "Eugênio de Castro", "Fagundes Varela", "Farroupilha", "Faxinal do Soturno", "Faxinalzinho", "Fazenda Vilanova", "Feliz", "Flores da Cunha", "Floriano Peixoto", "Fontoura Xavier", "Formigueiro", "Forquetinha", "Fortaleza dos Valos", "Frederico Westphalen", "Garibaldi", "Garruchos", "Gaurama", "General Câmara", "Gentil", "Getúlio Vargas", "Giruá", "Glorinha", "Gramado dos Loureiros", "Gramado Xavier", "Gramado", "Gravataí", "Guabiju", "Guaíba", "Guaporé", "Guarani das Missões", "Harmonia", "Herval", "Herveiras", "Horizontina", "Hulha Negra", "Humaitá", "Ibarama", "Ibiaçá", "Ibiraiaras", "Ibirapuitã", "Ibirubá", "Igrejinha", "Ijuí", "Ilópolis", "Imbé", "Imigrante", "Independência", "Inhacorá", "Ipê", "Ipiranga do Sul", "Iraí", "Itaara", "Itacurubi", "Itapuca", "Itaqui", "Itati", "Itatiba do Sul", "Ivorá", "Ivoti", "Jaboticaba", "Jacuizinho", "Jacutinga", "Jaguarão", "Jaguari", "Jaquirana", "Jari", "Jóia", "Júlio de Castilhos", "Lagoa Bonita do Sul", "Lagoa dos Três Cantos", "Lagoa Vermelha", "Lagoão", "Lajeado", "Lajeado do Bugre", "Lavras do Sul", "Liberato Salzano", "Lindolfo Collor", "Linha Nova", "Maçambará", "Machadinho", "Mampituba", "Manoel Viana", "Maquiné", "Maratá", "Marau", "Marcelino Ramos", "Mariana Pimentel", "Mariano Moro", "Marques de Souza", "Mata", "Mato Castelhano", "Mato Leitão", "Mato Queimado", "Maximiliano de Almeida", "Minas do Leão", "Miraguaí", "Montauri", "Monte Alegre dos Campos", "Monte Belo do Sul", "Montenegro", "Mormaço", "Morrinhos do Sul", "Morro Redondo", "Morro Reuter", "Mostardas", "Muçum", "Muitos Capões", "Muliterno", "Não-Me-Toque", "Nicolau Vergueiro", "Nonoai", "Nova Alvorada", "Nova Araçá", "Nova Bassano", "Nova Boa Vista", "Nova Bréscia", "Nova Candelária", "Nova Esperança do Sul", "Nova Hartz", "Nova Pádua", "Nova Palma", "Nova Petrópolis", "Nova Prata", "Nova Ramada", "Nova Roma do Sul", "Nova Santa Rita", "Novo Barreiro", "Novo Cabrais", "Novo Hamburgo", "Novo Machado", "Novo Tiradentes", "Novo Xingu", "Osório", "Paim Filho", "Palmares do Sul", "Palmeira das Missões", "Palmitinho", "Panambi", "Pantano Grande", "Paraí", "Paraíso do Sul", "Pareci Novo", "Parobé", "Passa-Sete", "Passo do Sobrado", "Passo Fundo", "Paulo Bento", "Paverama", "Pedras Altas", "Pedro Osório", "Pejuçara", "Pelotas", "Picada Café", "Pinhal", "Pinhal da Serra", "Pinhal Grande", "Pinheirinho do Vale", "Pinheiro Machado", "Pinto Bandeira", "Pirapó", "Piratini", "Planalto", "Poço das Antas", "Pontão", "Ponte Preta", "Portão", "Porto Alegre", "Porto Lucena", "Porto Mauá", "Porto Vera Cruz", "Porto Xavier", "Pouso Novo", "Presidente Lucena", "Progresso", "Protásio Alves", "Putinga", "Quaraí", "Quatro Irmãos", "Quevedos", "Quinze de Novembro", "Redentora", "Relvado", "Restinga Sêca", "Rio dos Índios", "Rio Grande", "Rio Pardo", "Riozinho", "Roca Sales", "Rodeio Bonito", "Rolador", "Rolante", "Ronda Alta", "Rondinha", "Roque Gonzales", "Rosário do Sul", "Sagrada Família", "Saldanha Marinho", "Salto do Jacuí", "Salvador das Missões", "Salvador do  Sul", "Sananduva", "Santa Bárbara do Sul", "Santa Cecília do Sul", "Santa Clara do Sul", "Santa Cruz do Sul",  "Santa Margarida do Sul", "Santa Maria", "Santa Maria do Herval", "Santa Rosa", "Santa Tereza", "Santa Vitória do Palmar", "Santana da Boa Vista", "Sant'Ana do Livramento", "Santiago", "Santo Ângelo", "Santo Antônio da Patrulha", "Santo Antônio das Missões", "Santo Antônio do Palma", "Santo Antônio do Planalto", "Santo Augusto", "Santo Cristo", "Santo Expedito do Sul", "São Borja", "São Domingos do Sul", "São Francisco de Assis", "São Francisco de Paula", "São Gabriel", "São Jerônimo", "São João da Urtiga", "São João do Polêsine", "São Jorge", "São José das Missões", "São José do Herval", "São José do Hortêncio", "São José do Inhacorá", "São José do Norte", "São José do Ouro", "São José do Sul", "São José dos Ausentes", "São Leopoldo", "São Lourenço do Sul", "São Luiz Gonzaga", "São Marcos", "São Martinho", "São Martinho da Serra", "São Miguel das Missões", "São Nicolau", "São Paulo das Missões", "São Pedro da Serra", "São Pedro das Missões", "São Pedro do Butiá", "São Pedro do Sul", "São Sebastião do Caí", "São Sepé", "São Valentim", "São Valentim do Sul", "São Valério do Sul", "São Vendelino", "São Vicente do Sul", "Sapiranga", "Sapucaia do Sul", "Sarandi", "Seberi", "Sede Nova", "Segredo", "Selbach", "Senador Salgado Filho", "Sentinela do Sul", "Serafina Corrêa", "Sério", "Sertão", "Sertão Santana", "Sete de Setembro", "Severiano de Almeida", "Silveira Martins", "Sinimbu", "Sobradinho", "Soledade", "Tabaí", "Tapejara", "Tapera", "Tapes", "Taquara", "Taquari", "Taquaruçu do Sul", "Tavares", "Tenente Portela", "Terra de Areia", "Teutônia", "Tio Hugo", "Tiradentes do Sul", "Toropi", "Torres", "Tramandaí", "Travesseiro", "Três Arroios", "Três Cachoeiras", "Três Coroas", "Três de Maio", "Três Forquilhas", "Três Palmeiras", "Três Passos", "Trindade do Sul", "Triunfo", "Tucunduva", "Tunas", "Tupanci do Sul", "Tupanciretã", "Tupandi", "Tuparendi", "Turuçu", "Ubiretama", "União da Serra", "Unistalda", "Uruguaiana", "Vacaria", "Vale do Sol", "Vale Real", "Vale Verde", "Vanini", "Venâncio Aires", "Vera Cruz", "Veranópolis", "Vespasiano Corrêa", "Viadutos", "Viamão", "Vicente Dutra", "Victor Graeff", "Vila Flores", "Vila Lângaro", "Vila Maria", "Vila Nova do Sul", "Vista Alegre do Prata", "Vista Alegre", "Vista Gaúcha", "Vitória das Missões", "Westfália", "Xangri-lá"],
    SE:["Amparo de São Francisco", "Aquidabã", "Aracaju", "Arauá", "Areia Branca", "Barra dos Coqueiros", "Boquim", "Brejo Grande", "Campo do Brito", "Canhoba", "Canindé de São Francisco", "Capela", "Carira", "Carmópolis", "Cedro de São João", "Cristinápolis", "Cumbe", "Divina Pastora", "Estância", "Feira Nova", "Frei Paulo", "Gararu", "General Maynard", "Graccho Cardoso", "Ilha das Flores", "Indiaroba", "Itabaiana", "Itabaianinha", "Itabi", "Itaporanga d'Ajuda", "Japaratuba", "Japoatã", "Lagarto", "Laranjeiras", "Macambira", "Malhada dos Bois", "Malhador", "Maruim", "Moita Bonita", "Monte Alegre de Sergipe", "Muribeca", "Neópolis", "Nossa Senhora Aparecida", "Nossa Senhora da Glória", "Nossa Senhora das Dores", "Nossa Senhora de Lourdes", "Nossa Senhora do Socorro", "Pacatuba", "Pedra Mole", "Pedrinhas", "Pinhão", "Pirambu", "Poço Redondo", "Poço Verde", "Porto da Folha", "Propriá", "Riachão do Dantas", "Riachuelo", "Ribeirópolis", "Rosário do Catete", "Salgado", "Santa Luzia do Itanhy", "Santa Rosa de Lima", "Santana do São Francisco", "Santo Amaro das Brotas", "São Cristóvão", "São Domingos", "São Francisco", "São Miguel do Aleixo", "Simão Dias", "Siriri", "Telha", "Tobias Barreto", "Tomar do Geru", "Umbaúba"],
    TO: ["Abreulândia", "Aguiarnópolis", "Aliança do Tocantins", "Almas", "Alvorada", "Ananás", "Angico", "Aparecida do Rio Negro", "Aragominas", "Araguacema", "Araguaçu", "Araguaína", "Araguanã", "Araguatins", "Arapoema", "Arraias", "Augustinópolis", "Aurora do Tocantins", "Axixá do Tocantins", "Babaçulândia", "Bandeirantes do Tocantins", "Barra do Ouro", "Barrolândia", "Bernardo Sayão", "Bom Jesus do Tocantins", "Brasilândia do Tocantins", "Brejinho de Nazaré", "Buriti do Tocantins", "Cachoeirinha", "Campos Lindos", "Cariri do Tocantins", "Carmolândia", "Carrasco Bonito", "Caseara", "Centenário", "Chapada da Natividade", "Chapada de Areia", "Colinas do Tocantins", "Colméia", "Combinado", "Conceição do Tocantins", "Couto Magalhães", "Cristalândia", "Crixás do Tocantins", "Darcinópolis", "Dianópolis", "Divinópolis do Tocantins", "Dois Irmãos do Tocantins", "Dueré", "Esperantina", "Fátima", "Figueirópolis", "Filadélfia", "Formoso do Araguaia", "Tabocão", "Goianorte", "Goiatins", "Guaraí", "Gurupi", "Ipueiras", "Itacajá", "Itaguatins", "Itapiratins", "Itaporã do Tocantins", "Jaú do Tocantins", "Juarina", "Lagoa da Confusão", "Lagoa do Tocantins", "Lajeado", "Lavandeira", "Lizarda", "Luzinópolis", "Marianópolis do Tocantins", "Mateiros", "Maurilândia do Tocantins", "Miracema do Tocantins", "Miranorte", "Monte do Carmo", "Monte Santo do Tocantins", "Muricilândia", "Natividade", "Nazaré", "Nova Olinda", "Nova Rosalândia", "Novo Acordo", "Novo Alegre", "Novo Jardim", "Oliveira de Fátima", "Palmas", "Palmeirante", "Palmeiras do Tocantins", "Palmeirópolis", "Paraíso do Tocantins", "Paranã", "Pau d'Arco", "Pedro Afonso", "Peixe", "Pequizeiro", "Pindorama do Tocantins", "Piraquê", "Pium", "Ponte Alta do Bom Jesus", "Ponte Alta do Tocantins", "Porto Alegre do Tocantins", "Porto Nacional", "Praia Norte", "Presidente Kennedy", "Pugmil", "Recursolândia", "Riachinho", "Rio da Conceição", "Rio dos Bois", "Rio Sono", "Sampaio", "Sandolândia", "Santa Fé do Araguaia", "Santa Maria do Tocantins", "Santa Rita do Tocantins", "Santa Rosa do Tocantins", "Santa Tereza do Tocantins", "Santa Terezinha do Tocantins", "São Bento do Tocantins", "São Félix do Tocantins", "São Miguel do Tocantins", "São Salvador do Tocantins", "São Sebastião do Tocantins", "São Valério da Natividade", "Silvanópolis", "Sítio Novo do Tocantins", "Sucupira", "Taguatinga", "Taipas do Tocantins", "Talismã", "Tocantínia", "Tocantinópolis", "Tupirama", "Tupiratins", "Wanderlândia", "Xambioá"]
  };

  function carregarCidades() {
    const estadoSelect = document.getElementById("estado");
    const cidadeSelect = document.getElementById("cidade");
    const estadoSelecionado = estadoSelect.value;

    // Limpa o select de cidades
    cidadeSelect.innerHTML = '<option value="">Selecione uma cidade</option>';

    if (estadoSelecionado && cidadesPorEstado[estadoSelecionado]) {
      cidadesPorEstado[estadoSelecionado].forEach(cidade => {
        const option = document.createElement("option");
        option.value = cidade;
        option.textContent = cidade;
        cidadeSelect.appendChild(option);
      });
    }
  }
</script>

      <button type="submit">Cadastrar Clínica</button>
    </form>
  </div>
  

</main>
<script>
    const cnpjInput = document.getElementById('cnpj');
     const cpfInput = document.getElementById('cpf');
  const cepInput = document.getElementById('cep');

 document.addEventListener('DOMContentLoaded', function () {
  const telefones = document.querySelectorAll('.telefone');

  telefones.forEach(function (input) {
    input.addEventListener('input', function () {
      let valor = input.value.replace(/\D/g, '');

      if (valor.length > 11) valor = valor.slice(0, 11);

      if (valor.length > 10) {
        valor = valor.replace(/^(\d{2})(\d{5})(\d{4})$/, "($1) $2-$3");
      } else if (valor.length > 6) {
        valor = valor.replace(/^(\d{2})(\d{4})(\d{0,4})$/, "($1) $2-$3");
      } else if (valor.length > 2) {
        valor = valor.replace(/^(\d{2})(\d{0,5})$/, "($1) $2");
      } else {
        valor = valor.replace(/^(\d*)$/, "($1");
      }

      input.value = valor;
    });
  });
});


  cnpjInput.addEventListener('input', () => {
    let valor = cnpjInput.value.replace(/\D/g, '');

    if (valor.length > 14) valor = valor.slice(0, 14);

    if (valor.length > 12) {
      valor = valor.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2}).*/, "$1.$2.$3/$4-$5");
    } else if (valor.length > 8) {
      valor = valor.replace(/^(\d{2})(\d{3})(\d{3})(\d{0,4})/, "$1.$2.$3/$4");
    } else if (valor.length > 5) {
      valor = valor.replace(/^(\d{2})(\d{3})(\d{0,3})/, "$1.$2.$3");
    } else if (valor.length > 2) {
      valor = valor.replace(/^(\d{2})(\d{0,3})/, "$1.$2");
    }

    cnpjInput.value = valor;
  });

   document.addEventListener("DOMContentLoaded", function () {
  const cpfInputs = document.querySelectorAll('.cpf');

  cpfInputs.forEach(input => {
    input.addEventListener('input', function () {
      let valor = this.value.replace(/\D/g, '');
      if (valor.length > 11) valor = valor.slice(0, 11);

      if (valor.length > 9) {
        valor = valor.replace(/^(\d{3})(\d{3})(\d{3})(\d{2})$/, "$1.$2.$3-$4");
      } else if (valor.length > 6) {
        valor = valor.replace(/^(\d{3})(\d{3})(\d{0,3})$/, "$1.$2.$3");
      } else if (valor.length > 3) {
        valor = valor.replace(/^(\d{3})(\d{0,3})$/, "$1.$2");
      }

      this.value = valor;
    });
  });
});

  if (cepInput) {
    cepInput.addEventListener('input', function () {
      let valor = this.value.replace(/\D/g, '');
      if (valor.length > 8) valor = valor.slice(0, 8);

      if (valor.length > 5) {
        valor = valor.replace(/^(\d{5})(\d{0,3})$/, "$1-$2");
      }

      this.value = valor;
    });
  }

  (function() {

  function maskCRM(input) {
    let v = input.value.toUpperCase();

    // Remover tudo que não seja letra ou número
    v = v.replace(/[^A-Z0-9]/g, '');

    // SE começar com 2 letras (UF)
    let uf = '';
    let num = '';

    if (/^[A-Z]{2}/.test(v)) {
      uf = v.substring(0, 2);
      num = v.substring(2);
    } else {
      // Se o usuário digitar só números ou inverter (ex: 12345SP)
      let letters = v.replace(/[^A-Z]/g, '');
      let numbers = v.replace(/[^0-9]/g, '');

      if (letters.length >= 2) {
        uf = letters.substring(0, 2);
        num = numbers;
      } else {
        uf = v.replace(/[^A-Z]/g, '').substring(0, 2);
        num = v.replace(/[^0-9]/g, '');
      }
    }

    // Limitar CRM a 7 dígitos
    num = num.substring(0, 7);

    // Montar e colocar no campo
    input.value = uf ? `${uf}/${num}` : num;
  }

  // Aplicar em inputs com classe "mask-crm"
  document.addEventListener('input', function(e){
    if (e.target.classList.contains('mask-crm')) {
      maskCRM(e.target);
    }
  });

})();

</script>

<script>
const btnLoginTab = document.getElementById('btnLoginTab');
const btnRegisterTab = document.getElementById('btnRegisterTab');
const pacienteTab = document.getElementById('pacienteTab');
const clinicaTab = document.getElementById('clinicaTab');
const medicoTab = document.getElementById('medicoTab');

const loginPacienteForm = document.getElementById('loginPacienteForm');
const loginClinicaForm = document.getElementById('loginClinicaForm');
const loginMedicoForm = document.getElementById('loginMedicoForm');
const cadastroPacienteForm = document.getElementById('cadastroPacienteForm');
const cadastroClinicaForm = document.getElementById('cadastroClinicaForm');
const cadastroMedicoForm = document.getElementById('cadastroMedicoForm');

let currentMainTab = 'login';
let currentSubTab = 'paciente';

function updateForms() {
  // esconde todos
  [
    loginPacienteForm,
    loginClinicaForm,
    loginMedicoForm,
    cadastroPacienteForm,
    cadastroClinicaForm,
    cadastroMedicoForm
  ].forEach(f => f.classList.remove('active'));

  // mostra o certo
  if (currentMainTab === 'login') {
    if (currentSubTab === 'paciente') loginPacienteForm.classList.add('active');
    else if (currentSubTab === 'clinica') loginClinicaForm.classList.add('active');
    else if (currentSubTab === 'medico') loginMedicoForm.classList.add('active');
  } else if (currentMainTab === 'cadastro') {
    if (currentSubTab === 'paciente') cadastroPacienteForm.classList.add('active');
    else if (currentSubTab === 'clinica') cadastroClinicaForm.classList.add('active');
    else if (currentSubTab === 'medico') cadastroMedicoForm.classList.add('active');
  }
}

// troca Login/Cadastro
btnLoginTab.onclick = () => {
  currentMainTab = 'login';
  btnLoginTab.classList.add('active');
  btnRegisterTab.classList.remove('active');
  updateForms();
};
btnRegisterTab.onclick = () => {
  currentMainTab = 'cadastro';
  btnRegisterTab.classList.add('active');
  btnLoginTab.classList.remove('active');
  updateForms();
};

// troca sub-abas
pacienteTab.onclick = () => {
  currentSubTab = 'paciente';
  pacienteTab.classList.add('active');
  clinicaTab.classList.remove('active');
  medicoTab.classList.remove('active');
  updateForms();
};
clinicaTab.onclick = () => {
  currentSubTab = 'clinica';
  clinicaTab.classList.add('active');
  pacienteTab.classList.remove('active');
  medicoTab.classList.remove('active');
  updateForms();
};
medicoTab.onclick = () => {
  currentSubTab = 'medico';
  medicoTab.classList.add('active');
  pacienteTab.classList.remove('active');
  clinicaTab.classList.remove('active');
  updateForms();
};

updateForms();
</script>

</body>
</html>
