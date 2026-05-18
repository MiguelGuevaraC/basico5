(() => {
    const getBaseUrl = () => {
        if (typeof window.APP_BASE_URL === 'string' && window.APP_BASE_URL !== '') {
            return window.APP_BASE_URL.replace(/\/+$/, '');
        }
        return '';
    };

    const buildUrl = (path) => {
        const base = getBaseUrl();
        if (typeof path !== 'string') {
            return base || '/';
        }
        const normalized = path.startsWith('/') ? path : `/${path}`;
        return `${base}${normalized}`;
    };

    const csrfToken = () => {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    };

    const ensureJson = async (response) => {
        const contentType = response.headers.get('content-type') || '';
        if (!contentType.includes('application/json')) {
            const text = await response.text();
            throw new Error(text || 'Respuesta inválida del servidor.');
        }
        return response.json();
    };

    const extractMessage = (payload, fallback) => {
        if (payload && typeof payload.message === 'string' && payload.message.trim() !== '') {
            return payload.message;
        }
        if (payload && payload.errors && typeof payload.errors === 'object') {
            const firstKey = Object.keys(payload.errors)[0];
            const firstError = firstKey ? payload.errors[firstKey] : null;
            if (Array.isArray(firstError) && typeof firstError[0] === 'string') {
                return firstError[0];
            }
        }
        return fallback;
    };

    const getJson = async (path) => {
        const response = await fetch(buildUrl(path), {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
            },
            credentials: 'same-origin',
        });

        const payload = await ensureJson(response);
        if (!response.ok || !payload || payload.success === false) {
            throw new Error(extractMessage(payload, 'Error al consultar el servidor.'));
        }

        return payload;
    };

    const buildFormData = (data) => {
        const formData = new FormData();
        Object.entries(data || {}).forEach(([key, value]) => {
            if (value instanceof FileList) {
                for (let i = 0; i < value.length; i++) {
                    formData.append(key + '[]', value[i]);
                }
            } else if (Array.isArray(value)) {
                value.forEach((item) => {
                    formData.append(key + '[]', item);
                });
            } else if (value !== null && value !== undefined) {
                formData.append(key, value);
            }
        });
        return formData;
    };

    const postForm = async (path, data) => {
        const hasFiles = data && Object.values(data).some(v => v instanceof FileList);
        let body, headers;

        if (hasFiles) {
            body = buildFormData(data);
            headers = {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken(),
            };
        } else {
            body = new URLSearchParams();
            Object.entries(data || {}).forEach(([key, value]) => {
                body.set(key, value === null || value === undefined ? '' : String(value));
            });
            headers = {
                'Accept': 'application/json',
                'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                'X-CSRF-TOKEN': csrfToken(),
            };
            body = body.toString();
        }

        const response = await fetch(buildUrl(path), {
            method: 'POST',
            headers,
            credentials: 'same-origin',
            body,
        });

        const payload = await ensureJson(response);
        if (!response.ok || !payload || payload.success === false) {
            throw new Error(extractMessage(payload, 'Error al procesar la solicitud.'));
        }

        return payload;
    };

    window.AppHttp = {
        buildUrl,
        getJson,
        postForm,
    };
})();
