const API = "/mochiSearch/api/api.php?endpoint=";

async function cargarSelect(id, endpoint, campo) {
  const select = document.getElementById(id);
  if (!select) return; // evita el error si el elemento no existe

  const res = await fetch(API + endpoint);
  const datos = await res.json();
  select.innerHTML = `<option value="">-- Todos --</option>`;
  datos.forEach(item => {
    select.innerHTML += `<option value="${item[campo]}">${item[campo]}</option>`;
  });
}


async function cargarTodos() {
  const res = await fetch(API + "becarios");
  const datos = await res.json();
  mostrarResultados(datos);
}

function mostrarResultados(datos) {
  const tbody = document.getElementById("tablaResultados");
  tbody.innerHTML = "";

  if (datos.length === 0) {
    tbody.innerHTML = `<tr><td colspan="5">No se encontraron resultados</td></tr>`;
    return;
  }

  datos.forEach(becario => {
    tbody.innerHTML += `
      <tr>
        <td>${becario.NOMBRES}</td>
        <td>${becario.APELLIDOS}</td>
        <td>${becario.SEXO}</td>
        <td>${becario.DEPARTAMENTO_RESIDENCIA || "-"}</td>
        <td>${becario.TIPO_BECA_RESUMEN || "-"}</td>
        <td>${becario.AREA_CIENCIA}</td>
        <td>${becario.SUBAREA_CIENCIA}</td>
        <td>${becario.NOMBRE_PROGRAMA_ESTUDIO}</td>
        <td>${becario.UNIVERSIDAD}</td>
        <td>${becario.PAIS_DESTINO}</td>
        <td>${becario.CIUDAD_DESTINO}</td>
        <td>${becario.CVPY}</td>
      </tr>`;
  });
}

async function buscar() {
    const nombre = document.getElementById("nombre").value.trim();
    const pais = document.getElementById("pais").value;
    const depto = document.getElementById("departamento").value;
    const tipo_beca = document.getElementById("tipo_beca").value;
    
    let url = API + "filtrar_avanzado";
    let params = [];

    if (nombre) params.push(`nombre=${encodeURIComponent(nombre)}`);
    if (pais) params.push(`pais=${encodeURIComponent(pais)}`);
    if (depto) params.push(`departamento=${encodeURIComponent(depto)}`);
    if (tipo_beca) params.push(`tipo_beca=${encodeURIComponent(tipo_beca)}`);

    if (params.length === 0) {
        cargarTodos(); // Si no hay filtros, mostrar todo
        return;
    }

    url += "&" + params.join("&");
    const res = await fetch(url);
    const datos = await res.json();
    mostrarResultados(datos);
}

document.getElementById("buscarBtn").addEventListener("click", buscar);
cargarSelect("pais", "paises", "pais");
cargarSelect("departamento", "departamentos", "departamento");
cargarSelect("tipo_beca", "tipos_beca", "tipo_beca");
cargarTodos(); //mostrar todos los becarios al cargar