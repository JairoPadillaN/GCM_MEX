let Cosmic = {
  table: ({
    container,
    url,
    method,
    headers,
    tableElements,
    classes,
    paginate,
  }) => {
    let containers = {
      table: document.createElement("table"),
      tableContainer: document.querySelector(container),
      tableHeader: document.createElement("thead"),
      tableBody: document.createElement("tbody"),
      tableFooter: document.createElement("tfoot"),
      paginateRow: document.createElement("tr"),
      paginateColumn: document.createElement("th"),
      paginateSelect: document.createElement("select"),
    };
    let functions = {
      createTable: () => {
        classes.forEach((element) => {
          containers.table.className += element + " ";
        });
        containers.tableContainer.appendChild(containers.table);
        containers.table.appendChild(containers.tableHeader);
        containers.table.appendChild(containers.tableBody);
        containers.table.appendChild(containers.tableFooter);
        containers.tableFooter.innerHTML = '<tr id="cosmic-footer-suma"></tr>';

        containers.table.setAttribute("id", "cosmic-table");
        containers.tableHeader.setAttribute("id", "cosmic-table-header");
        containers.tableBody.setAttribute("id", "cosmic-table-body");
        containers.tableFooter.setAttribute("id", "cosmic-table-footer");
      },
      appendingHeader: () => {
        let count = 0;
        let tableHeaderRow = document.createElement("tr");
        tableElements.forEach((element) => {
          let column = document.createElement("th");
          column.innerHTML = element.name.toUpperCase();
          column.setAttribute("id", `cosmic-header-${element.name[count]}`);
          tableHeaderRow.appendChild(column);

          count = count == tableElements.length ? 0 : count + 1;
        });
        containers.tableHeader.appendChild(tableHeaderRow);
      },
      appendingFilters: (results) => {
        let tableFilterRow = document.createElement("tr");
        tableElements.forEach((element) => {
          let filterColumn = document.createElement("td");
          let filter = element.filter
            ? document.createElement("input")
            : document.createElement("span");
          if (element.filter) {
            filter.setAttribute("id", `cosmic-filter-${element.name}`);
            filter.className = "form-control";

            filter.addEventListener("keyup", (e) => {
              let newElements = results.filter((el) => {
                return el[element.column]
                  .toLowerCase()
                  .indexOf(filter.value.toLowerCase()) >= 0
                  ? element
                  : "";
              });

              containers.tableBody.innerHTML = "";
              functions.appendingPaginatedRows(1, newElements);
            });
          }
          filterColumn.appendChild(filter);
          tableFilterRow.appendChild(filterColumn);
        });
        containers.tableHeader.appendChild(tableFilterRow);
      },
      appendingRows: (results) => {
        results.forEach((element) => {
          let tableRow = document.createElement("tr");
          containers.tableBody.appendChild(tableRow);
          tableElements.forEach((el) => {
            let column = document.createElement("td");
            column.innerHTML = element[el];
            column.setAttribute("id", `column-${element[el]}`);
            tableRow.appendChild(column);
          });
        });
      },
      createPaginateSelect: () => {
        containers.paginateColumn.setAttribute("colspan", tableElements.length);
        containers.paginateColumn.setAttribute("style", "text-align: center;");
        containers.paginateRow.appendChild(containers.paginateColumn);

        containers.paginateColumn.innerHTML = "Pagina: ";
        containers.paginateColumn.appendChild(containers.paginateSelect);
      },
      appendingPaginatedRows: (paginateOption, results) => {
        if (paginateOption <= results.length) {
          for (
            var index = paginate * paginateOption - paginate;
            index < paginate * paginateOption;
            index++
          ) {
            let tableRow = document.createElement("tr");
            containers.tableBody.appendChild(tableRow);
            let count = 0;
            tableElements.forEach((el) => {
              let column = document.createElement("td");
              column.innerHTML = results[index][el.column];
              column.setAttribute(
                "id",
                `cosmic-column-${el.column[count]}-${index}}`
              );
              tableRow.appendChild(column);
              count = count == tableElements.length ? 0 : count + 1;
            });
          }
        }
      },
      execute: () => {
        functions.createTable();
        functions.appendingHeader();
        fetch(
          url,
          method == "POST"
            ? {
                method: method,
                body: params,
                headers: headers,
              }
            : {
                method: method,
              }
        )
          .then((response) => response.json())
          .then((data) => {
            let results = data;
            if (paginate == null || paginate <= 0 || paginate == "") {
              functions.appendingRows(results);
            } else {
              functions.createPaginateSelect();
              functions.appendingFilters(results);
              let count = results.length / paginate;
              for (var i = 0; i < count; i++) {
                let paginateOption = document.createElement("option");
                paginateOption.value = i + 1;
                paginateOption.innerText = i + 1;
                containers.paginateSelect.appendChild(paginateOption);

                paginateOption.addEventListener("click", () => {
                  containers.tableBody.innerHTML = "";
                  functions.appendingPaginatedRows(
                    paginateOption.value,
                    results
                  );
                });
              }
              containers.tableFooter.appendChild(containers.paginateRow);
              functions.appendingPaginatedRows(1, results);
            }
          });
      },
    };
    if (container == null || container == "") {
      console.warn("No se declaro contenedor para la tabla");
    }
    if (url == null || url == "") {
      console.warn("Sin url");
    } else {
      functions.execute();
    }
  },
  createSumaRow: (results) => {
    let tableRow = document.createElement("tr");
    tableRow.setAttribute("id", "cosmic-footer-sumas");
    tableElements.forEach((element) => {
      let col = document.createElement("td");
      if (element.suma) {
        let suma = 0;
        results.forEach((el) => {
          console.log(el);
        });
      }
    });
  },
  beginLoadingAnimation: () => {
    let containers = {
      body: document.querySelector("body"),
    };
    let functions = {
      appendingStyleSection: () => {
        let styleTag = document.createElement("style");
        styleTag.setAttribute("id", "cosmic-style-animation-keyframes");
        styleTag.innerHTML = `@keyframes rotate-one { 0% { transform: rotateX(35deg) rotateY(-45deg) rotateZ(0deg);}
                              100% { transform: rotateX(35deg) rotateY(-45deg) rotateZ(360deg); } }
                              @keyframes rotate-two { 0% { transform: rotateX(50deg) rotateY(10deg) rotateZ(0deg); }
                              100% { transform: rotateX(50deg) rotateY(10deg) rotateZ(360deg); } }
                              @keyframes rotate-three { 0% { transform: rotateX(35deg) rotateY(55deg) rotateZ(0deg); } 
                              100% { transform: rotateX(35deg) rotateY(55deg) rotateZ(360deg); } }
                              .inner {
                                position: absolute;
                                box-sizing: border-box;
                                width: 100%;
                                height: 100%;
                                border-radius: 50%;  
                              }`;
        containers.body.appendChild(styleTag);
      },
      appendingLoadingSection: (element) => {
        let section = document.querySelector(element);
        let animationContainer = document.createElement("div");
        let lineOne = document.createElement("div");
        let lineTwo = document.createElement("div");
        let lineThree = document.createElement("div");
        animationContainer.setAttribute(
          "style",
          "position: absolute; top: 10; left: calc(50% - 32px); width: 64px; height: 64px; border-radius: 50%; perspective: 800px;"
        );
        animationContainer.setAttribute("id", "cosmic-loading-section");
        lineOne.setAttribute(
          "style",
          "left: 0%; top: 0%; animation: rotate-one 1s linear infinite; border-bottom: 3px solid #000;"
        );
        lineOne.setAttribute("id", "cosmic-animation-line-one");
        lineTwo.setAttribute("id", "cosmic-animation-line-twoo");
        lineThree.setAttribute("id", "cosmic-animation-line-three");
        lineOne.className = "inner";
        lineTwo.className = "inner";
        lineThree.className = "inner";
        lineTwo.setAttribute(
          "style",
          "right: 0%; top: 0%; animation: rotate-two 1s linear infinite; border-right: 3px solid #000;"
        );
        lineThree.setAttribute(
          "style",
          "right: 0%; bottom: 0%; animation: rotate-three 1s linear infinite; border-top: 3px solid #000;"
        );
        animationContainer.appendChild(lineOne);
        animationContainer.appendChild(lineTwo);
        animationContainer.appendChild(lineThree);
        section.appendChild(animationContainer);
      },
    };

    functions.appendingLoadingSection("#loading");
    functions.appendingStyleSection();
  },
  stopLoadingAnimation: () => {
    let functions = {
      removeLoadingSection: (element) => {
        let loadingSection = document.querySelector("#cosmic-loading-section");
        let lineOne = document.querySelector("#cosmic-animation-line-one");
        let lineTwo = document.querySelector("#cosmic-animation-line-twoo");
        let lineThree = document.querySelector("#cosmic-animation-line-three");
        loadingSection.removeChild(lineOne);
        loadingSection.removeChild(lineTwo);
        loadingSection.removeChild(lineThree);
        let loadingContainer = document.querySelector(element);
        loadingContainer.removeChild(loadingSection);
      },
      removeStyleSection: () => {
        let styleSection = document.querySelector(
          "#cosmic-style-animation-keyframes"
        );
        document.querySelector("body").removeChild(styleSection);
      },
    };
    functions.removeLoadingSection("#loading");
    functions.removeStyleSection();
  },
};
