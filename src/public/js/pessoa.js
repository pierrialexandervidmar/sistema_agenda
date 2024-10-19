$(document).ready(function () {
  configurarEventos();
  atualizarTabela();
});

function configurarEventos() {
  $('#form-pessoa').on('submit', enviarFormularioPessoa);
  $('#form-editar-pessoa').on('submit', enviarModalEdicao);

  acaoExclusao();
  acaoEdicao();
}

function enviarFormularioPessoa(e) {
  e.preventDefault();

  var nome = $('#descricao').val();
  var cpf = $('#cpf').val();

  if (nome === '' || cpf == '') {
      alert('Preencha todos os campos.');
      return;
  }

  var formData = {
    nome: $('#nome').val(),
    cpf: $('#cpf').val(),
  };

  $.ajax({
    type: 'POST',
    url: '/pessoas',  // Rota para salvar a pessoa no backend
    data: formData,
    success: function (response) {
      atualizarTabela();
      $('#nome').val('');
      $('#cpf').val('');
    },
    error: function (response) {
      if (response.responseJSON.erro) {
        alert('Erro ao editar a pessoa. Verifique os dados. ' + response.responseJSON.erro);
      }
      else {
        alert('Erro ao adicionar a pessoa. Verifique os dados.');
      }
    }
  });
}


function atualizarTabela() {
  $.ajax({
      url: '/pessoas',
      type: 'GET',
      success: function (data) {
          var pessoas = $(data).find('tbody').html();
          $('tbody').html(pessoas);
          acaoExclusao();
          acaoEdicao();
      },
      error: function () {
          alert('Erro ao carregar a lista de contatos.');
      }
  });
}

function acaoExclusao() {
  $('.btn-excluir').off('click').on('click', function (e) {
      e.preventDefault();
      var pessoaId = $(this).data('id');

      if (confirm('Tem certeza que deseja excluir este cadastro?')) {
          $.ajax({
              url: '/pessoas/excluir',
              type: 'POST',
              data: { id: pessoaId },
              success: function (response) {
                  atualizarTabela();
              },
              error: function (xhr, status, error) {
                  alert('Ocorreu um erro ao excluir o contato.');
              }
          });
      }
  });
}

function acaoEdicao() {
  $('.btn-editar').on('click', function (e) {
      e.preventDefault();
      var pessoaId = $(this).data('id');
      obterPessoa(pessoaId);
  });
}

function obterPessoa(id) {
  $.ajax({
      url: '/pessoas/' + id,
      type: 'GET',
      success: function(data) {
          console.log(data);
          $('#modalId').val(data.id);
          $('#modalNome').val(data.nome);
          $('#modalCpf').val(data.cpf);
          $('#modal').modal('show');
      },
      error: function(xhr, status, error) {
          alert('Erro ao obter os dados da pessoa.');
          console.log(error)
      }
  });
}


function enviarModalEdicao(e) {
  e.preventDefault();

  var formData = {
      id: $('#modalId').val(),
      nome: $('#modalNome').val(),
      cpf: $('#modalCpf').val()
  };

  $.ajax({
      type: 'POST',
      url: '/pessoas/editar',
      data: formData,
      success: function (response) {
          atualizarTabela();
          $('#modal').modal('hide'); // Corrigido para esconder o modal
      },
      error: function (response) {
          alert('Erro ao editar a pessoa. Verifique os dados. ' + response.responseJSON.erro);
      }
  }); // Corrigido para fechar a chamada da função
}

$(document).on('click', '#btn-fechar-edicao', function(e) {
  $('#modal').modal('hide');
});
