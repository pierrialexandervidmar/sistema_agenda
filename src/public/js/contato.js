$(document).ready(function () {
  configurarEventos();
});

function configurarEventos() {
  $('#form-contato').on('submit', enviarFormulario);
  $('#form-editar-contato').on('submit', enviarModalEdicao);
  acaoExclusao();
  acaoEdicao();
}

function enviarFormulario(e) {
  e.preventDefault();

  var descricao = $('#descricao').val().trim();

  if (descricao === '') {
      alert('Preencha a descrição do contato.');
      return;
  }

  var formData = {
      tipo: $('#tipo').val(),
      descricao: $('#descricao').val(),
      idPessoa: $('#pessoa').val(),
  };

  $.ajax({
      type: 'POST',
      url: '/contatos',
      data: formData,
      success: function (response) {
          atualizarTabela();
          $('#descricao').val('');
          $('#tipo').val('1');
          $('#pessoa').val('');
      },
      error: function (response) {
          alert('Erro ao adicionar contato. Verifique os dados.');
      }
  });
}

function atualizarTabela() {
  $.ajax({
      url: '/contatos',
      type: 'GET',
      success: function (data) {
          var contatos = $(data).find('tbody').html();
          $('tbody').html(contatos);
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
      var contatoId = $(this).data('id');

      if (confirm('Tem certeza que deseja excluir este contato?')) {
          $.ajax({
              url: '/contatos/excluir',
              type: 'POST',
              data: { id: contatoId },
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
      var contatoId = $(this).data('id');
      obterContato(contatoId);
  });
}

function obterContato(id) {
  $.ajax({
      url: '/contatos/' + id,
      type: 'GET',
      success: function(data) {
          console.log(data);
          $('#modalId').val(data.id);
          $('#modalDescricao').val(data.descricao);
          $('#modalTipo').val(data.tipo === 'Email' ? 1 : 0);
          $('#modalIdPessoa').val(data.pessoa.id);
          $('#modalNomePessoa').val(data.pessoa.nome);
          $('#modal').modal('show');
      },
      error: function(xhr, status, error) {
          alert('Erro ao obter os dados do contato.');
      }
  });
}

function enviarModalEdicao(e) {
  e.preventDefault();

  var formData = {
      id: $('#modalId').val(),
      tipo: $('#modalTipo').val(),
      descricao: $('#modalDescricao').val(),
      idPessoa: $('#modalIdPessoa').val(),
  };

  $.ajax({
      type: 'POST',
      url: '/contatos/editar',
      data: formData,
      success: function (response) {
          atualizarTabela();
          $('#modal').modal('hide'); // Corrigido para esconder o modal
      },
      error: function (response) {
          alert('Erro ao editar contato. Verifique os dados.');
      }
  }); // Corrigido para fechar a chamada da função
}

$(document).on('click', '#btn-fechar-edicao', function(e) {
  $('#modal').modal('hide');
});
