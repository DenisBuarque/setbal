@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-end">
        <a href="{{ route('admin.users.index') }}" class="btn btn-md btn-info" title="Adicionar novo registro">
            <i class="fa fa-table mr-1"></i> Listar registro
        </a>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <form method="POST" action="{{ route('admin.user.store') }}" enctype="multipart/form-data"
            onsubmit="return mySubmit()">
            @csrf
            <div class="card card-info" style="max-width: 1024px; margin: auto">
                <div class="card-header">
                    <h3 class="card-title">Formulário cadastro de usuário do sistema:</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-9">
                            <div class="form-group m-0">
                                <small>Nome do usuário: *</small>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="form-control @error('name') is-invalid @enderror">
                                @error('name')
                                    <div class="text-red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-0">
                                <small>Telefone: *</small>
                                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="82 9xxxx-xxxx"
                                    class="form-control" maxlength="13" onkeypress="mascara(this, '## #####-####')" />
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-0">
                                <small>Cep:</small>
                                <input type="text" name="zip_code" id="cep" value="{{ old('zip_code') }}"
                                    class="form-control" maxlength="9" onkeypress="mascara(this, '#####-###')"
                                    onblur="pesquisacep(this.value);" />
                            </div>
                        </div>
                        <div class="col-sm-9">
                            <div class="form-group m-0">
                                <small>Endereço:</small>
                                <input type="text" name="address" id="address" value="{{ old('address') }}"
                                    class="form-control" maxlength="250" />
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group m-0">
                                <small>Número:</small>
                                <input type="text" name="number" value="{{ old('number') }}"
                                    class="form-control" placeholder="nº" maxlength="5" />
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group m-0">
                                <small>Bairro:</small>
                                <input type="text" name="district" id="district" value="{{ old('district') }}"
                                    class="form-control" maxlength="50" />
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group m-0">
                                <small>Cidade:</small>
                                <input type="text" name="city" id="city" value="{{ old('city') }}"
                                    class="form-control" maxlength="50" />
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group m-0">
                                <small>Estado:</small>
                                <input type="text" name="state" id="state" value="{{ old('state') }}"
                                    class="form-control" maxlength="2" />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <small>E-mail: *</small>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror">
                                @error('email')
                                    <div class="text-red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <small>Senha: *</small>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" placeholder="Senha">
                                @error('password')
                                    <div class="text-red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <small>Confirme sua senha: *</small>
                                <input type="password" name="password_confirmation"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    placeholder="Confirmar a senha">
                                @error('password_confirmation')
                                    <div class="text-red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <small>Foto do usuário:</small>
                            <div class="form-group">
                                <input type="file" name="image" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <span>Permissões de acesso ao sistema: pressione e segure a tecla 'Ctrl', em seguida clique
                                    sobre a opção para selecionar.</span>
                                <select name="permission[]" class="form-control" multiple style="height: 170px;">
                                    @foreach ($permissions as $key => $value)
                                        @php
                                            $selected = '';
                                            if (old('permission')):
                                                foreach (old('permission') as $key => $value2):
                                                    if ($value2 == $value->id) {
                                                        $selected = 'selected';
                                                    }
                                                endforeach;
                                            endif;
                                        @endphp
                                        <option value="{{ $value->id }}" {{ $selected }}>{{ $value->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.users.index') }}" type="submit" class="btn btn-default">Cancelar</a>
                    <button id="button" type="submit" class="btn btn-md btn-info float-right" style="display: block;">
                        <i class="fas fa-save mr-1"></i>
                        Salvar dados
                    </button>
                    <a id="spinner" class="btn btn-md btn-info float-right text-center" style="display: none;">
                        <div id="spinner" class="spinner-border" role="status" style="width: 20px; height: 20px;">
                            <span class="sr-only">Loading...</span>
                        </div>
                        Enviando...
                    </a>
                </div>
            </div>
        </form>
        <br>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        document.getElementById("button").style.display = "block";
        document.getElementById("spinner").style.display = "none";

        function mySubmit() {
            document.getElementById("button").style.display = "none";
            document.getElementById("spinner").style.display = "block";
        }

        //criação de mascara
        function mascara(t, mask) {
            var i = t.value.length;
            var saida = mask.substring(1, 0);
            var texto = mask.substring(i)
            if (texto.substring(0, 1) != saida) {
                t.value += texto.substring(0, 1);
            }
        }

        // Busca pelo cep
        function limpa_formulario_cep() {
            //Limpa valores do formulário de cep.
            document.getElementById('address').value = ("");
            document.getElementById('district').value = ("");
            document.getElementById('city').value = ("");
            document.getElementById('state').value = ("");
        }

        function meu_callback(conteudo) {
            if (!("erro" in conteudo)) {
                //Atualiza os campos com os valores.
                document.getElementById('address').value = (conteudo.logradouro);
                document.getElementById('district').value = (conteudo.bairro);
                document.getElementById('city').value = (conteudo.localidade);
                document.getElementById('state').value = (conteudo.uf);
            } else {
                //CEP não Encontrado.
                limpa_formulário_cep();
                alert("CEP não encontrado.");
            }
        }

        function pesquisacep(valor) {
            //Nova variável "cep" somente com dígitos.
            var cep = valor.replace(/\D/g, '');
            //Verifica se campo cep possui valor informado.
            if (cep != "") {
                //Expressão regular para validar o CEP.
                var validacep = /^[0-9]{8}$/;
                //Valida o formato do CEP.
                if (validacep.test(cep)) {
                    //Preenche os campos com "..." enquanto consulta webservice.
                    document.getElementById('address').value = "...";
                    document.getElementById('district').value = "...";
                    document.getElementById('city').value = "...";
                    document.getElementById('state').value = "...";
                    //Cria um elemento javascript.
                    var script = document.createElement('script');
                    //Sincroniza com o callback.
                    script.src = 'https://viacep.com.br/ws/' + cep + '/json/?callback=meu_callback';
                    //Insere script no documento e carrega o conteúdo.
                    document.body.appendChild(script);

                } else {
                    //cep é inválido.
                    limpa_formulário_cep();
                    alert("Formato de CEP inválido.");
                }
            } //end if.
            else {
                //cep sem valor, limpa formulário.
                limpa_formulário_cep();
            }
        }
    </script>
@stop
