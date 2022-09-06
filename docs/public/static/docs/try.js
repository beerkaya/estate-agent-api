((window, undefined) => {
    window.initTry = window.initTry || initTry;

    function initTry(userCfg) {
        loadScript(
            `https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js`
        )
            .then(() =>
                loadScript(
                    `https://cdn.jsdelivr.net/npm/swagger-ui-dist@3.48.0/swagger-ui-bundle.js`
                )
            )
            .then(() => {
                initSwagger();
            })
            .catch(e => {
                console.error("Something went wrong.");
                console.error(e);
            });
    }

    function initSwagger() {
        const specKey = window.isProd ? "spec" : "url";
        const specValue = window.isProd ? redocSpec : "spec.json";

        SwaggerUIBundle({
            [specKey]: specValue,
            dom_id: `#swagger-ui`,
            onComplete: () => {
                trySwagger({
                    tryText: "Try It"
                });
            },
            tryItOutEnabled: true
        });
    }

    function trySwagger(cfg) {
        let parentButtonSelector = $(`.http-verb`)
            .parent("button")
            .parent("div")
            .parent("div");

        parentButtonSelector.css("position", "relative");

        let tryBtn = $(`<button class="tryBtn">${cfg.tryText}</button>`);

        // Add try button
        parentButtonSelector.append(tryBtn);

        $(`.tryBtn`).on("click", function(event) {
            event.stopPropagation();

            let $btn = $(this);

            let $operationWrapper = $btn
                .parent("div")
                .find("div")
                .first();

            let $operationButton = $operationWrapper.find("button").first();

            let method = $operationButton.find(".http-verb").attr("type");
            let api = $operationButton
                .find(".http-verb + span")
                .first()
                .text();

            hideSwaggerModal();

            // scroll the swagger view to the same api position
            const selStr = `.opblock-summary-${method} [data-path="${api}"]`;

            const $swaggerApiDom = $(selStr);

            const $opblock = $swaggerApiDom.parents(`.opblock`); // Get the currently clicked swagger api, and it is not an expanded element
            const section = $opblock.parent("span").first();

            const clickEvent = () => {
                section
                    .find("div > div.opblock-summary")
                    .first()
                    .click();
            };

            clickEvent();

            setTimeout(() => {
                section
                    .find(".responses-wrapper .responses-table")
                    .first()
                    .html("");

                openSwaggerModal(section);
            }, 500);
        });
    }

    function openSwaggerModal(html) {
        $("#swaggerModal #modalContent #contentInner").html(html);

        $("#swaggerModal").addClass("show");
    }

    function hideSwaggerModal() {
        $("#swaggerModal").removeClass("show");
    }

    document
        .getElementById("closeSwaggerModal")
        .addEventListener("click", hideSwaggerModal);

    function loadScript(src) {
        return new Promise((resolve, reject) => {
            const script = document.createElement("script");
            script.type = "text/javascript";
            script.onload = resolve;
            script.onerror = reject;
            script.src = src;
            document.head.append(script);
        });
    }
})(window);
