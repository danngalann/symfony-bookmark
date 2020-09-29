$(".favorito").on("click", function(e) {
  e.preventDefault();
  let $this = $(this),
    url = $this.data("url"),
    idMarcador = $this.data("id");

  $this.removeClass("activo");

  $.post(url, { id: idMarcador })
    .done((res) => {
      if (res.actualizado && res.favorito) {
        $this.addClass("activo");
      }
    })
    .fail(() => {
      $this.removeClass("activo");
    });
});
