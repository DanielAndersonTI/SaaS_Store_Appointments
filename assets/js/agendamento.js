document.addEventListener("DOMContentLoaded", () => {
    const dataHoraInput = document.getElementById("dataHora");

    // Simulação de horários ocupados (em produção, virá do banco via PHP)
    const horariosOcupados = {
        "2025-09-21": ["09:00", "10:00"], // Exemplo: domingo
        "2025-09-22": ["14:00", "15:30"]  // Exemplo: segunda
    };

    // Aviso ao concluir agendamento
    const form = document.querySelector("form");
    form.addEventListener("submit", (e) => {
        alert("Agendamento realizado com sucesso! Compareça com 10 minutos de antecedência. O atendimento dura cerca de 30 minutos, podendo chegar a 40 minutos em serviços completos.");
    });

    // Abrir calendário ao clicar no campo
    dataHoraInput.addEventListener("click", (e) => {
        e.stopPropagation(); // evita fechar logo ao abrir
        abrirCalendario();
    });

    function abrirCalendario() {
        // Remover calendário anterior se existir
        const existente = document.querySelector(".z-50");
        if (existente) existente.remove();

        const calendario = document.createElement("div");
        calendario.className = "bg-neutral-800 p-4 rounded-lg shadow-lg absolute z-50 mt-2 w-full max-w-md";
        calendario.style.maxHeight = "400px";   // limite de altura
        calendario.style.overflowY = "auto";    // scroll automático

        calendario.innerHTML = "<h3 class='text-lg font-bold mb-2'>Escolha uma data e horário</h3>";

        const hoje = new Date();
        const dias = 14; // exibir 14 dias (ajuste se quiser mais)

        for (let i = 0; i < dias; i++) {
            const dia = new Date(hoje);
            dia.setDate(hoje.getDate() + i);
            const dataStr = dia.toISOString().split("T")[0];
            const diaSemana = dia.getDay(); // 0 = domingo, 1 = segunda...

            const diaDiv = document.createElement("div");
            diaDiv.className = "mb-3";

            const label = document.createElement("p");
            label.textContent = dia.toLocaleDateString("pt-BR", { weekday: "long", day: "numeric", month: "short" });
            label.className = "font-semibold mb-1";
            diaDiv.appendChild(label);

            // Horários disponíveis por dia
            let horarios = [];
            if (diaSemana === 0) {
                horarios = gerarHorarios("09:00", "11:30");
            } else {
                horarios = [
                    ...gerarHorarios("09:00", "11:30"),
                    ...gerarHorarios("14:00", "18:30")
                ];
            }

            horarios.forEach(horario => {
                const ocupado = horariosOcupados[dataStr]?.includes(horario);
                const botao = document.createElement("button");
                botao.textContent = horario;
                botao.className = `px-4 py-2 rounded text-sm w-full sm:w-auto border ${
                    ocupado
                        ? "bg-neutral-700 text-gray-400 cursor-not-allowed border-transparent"
                        : "bg-amber-500 hover:bg-yellow-600 text-black border-yellow-600"
                }`;

                botao.disabled = ocupado;

                if (!ocupado) {
                    botao.addEventListener("click", () => {
                        const diaFormatado = `${String(dia.getDate()).padStart(2, "0")}/${String(dia.getMonth() + 1).padStart(2, "0")}/${dia.getFullYear()}`;
                        const horarioFormatado = horario.replace(":", "h");
                        dataHoraInput.value = `${diaFormatado}, às ${horarioFormatado}`;

                        calendario.remove();
                    });
                }

                diaDiv.appendChild(botao);
            });
            diaDiv.classList.add("grid", "grid-cols-2", "sm:grid-cols-3", "gap-3");

            calendario.appendChild(diaDiv);
        }

        // Inserir calendário abaixo do campo
        dataHoraInput.parentNode.appendChild(calendario);
    }

    // Gera horários com intervalo de 30 minutos
    function gerarHorarios(inicio, fim) {
        const horarios = [];
        let [h, m] = inicio.split(":").map(Number);
        const [hf, mf] = fim.split(":").map(Number);

        while (h < hf || (h === hf && m <= mf)) {
            const horaFormatada = `${String(h).padStart(2, "0")}:${String(m).padStart(2, "0")}`;
            horarios.push(horaFormatada);
            m += 30;
            if (m >= 60) {
                h += 1;
                m -= 60;
            }
        }

        return horarios;
    }

    // Fecha ao clicar fora
    document.addEventListener("click", (e) => {
        const calendario = document.querySelector(".z-50");
        if (calendario && !calendario.contains(e.target) && e.target !== dataHoraInput) {
            calendario.remove();
        }
    });

    const telefoneInput = document.getElementById("telefone");
    telefoneInput.addEventListener("input", (e) => {
        let valor = e.target.value.replace(/\D/g, ""); // só números

        if (valor.length > 11) valor = valor.slice(0, 11); // limita 11 dígitos

        if (valor.length > 6) {
            e.target.value = `(${valor.slice(0,2)}) ${valor.slice(2,7)}-${valor.slice(7)}`;
        } else if (valor.length > 2) {
            e.target.value = `(${valor.slice(0,2)}) ${valor.slice(2)}`;
        } else {
            e.target.value = valor;
        }
    });
});
