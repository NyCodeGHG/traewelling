// @ts-check
'use strict';
import { notyf } from "../app";

/**
 * @typedef {"GET" | "POST" | "PUT" | "PATCH" | "DELETE"} HttpMethod
 * @typedef {Object} Options<T>
 * @property {HttpMethod} method The http method used.
 * @property {(value: Response) => Promise<unknown>} parser The parser used for the response body.
 * @property {any | null} body The body to send.
 * @property {(value) => string} bodyHandler The handler which converts an arbitary object into a string for the response.
 * @property {Record<string, string>} headers Headers sent in the request.
 * @property {(reason: any) => void} errorHandler Error handler for the request.
 */

/**
 * @type {Options}
 */
const defaultOptions = {
    method: "GET",
    parser: value => value.json(),
    body: null,
    bodyHandler: value => typeof value === "string" ? value : JSON.stringify(value),
    headers: {
        "Content-Type": "application/json",
    },
    errorHandler: (reason) => {
        console.error(reason);
        let errorMessage = reason?.message ?? reason?.data?.message ?? 'An unknown error occured.';
        notyf.error(errorMessage);
    },
};
/**
 * Makes a request to Träwelling's API
 * @param {string} path the request path to use.
 * @param {Partial<Options>} options the options to use for the request.
 */
export function request(path, options = {}) {
    // Merge options with default options.
    const requestOptions = {
        ...defaultOptions,
        ...options,
    };
    let body;
    if (requestOptions.body !== null) {
        body = requestOptions.bodyHandler(requestOptions.body);
    }
    return fetch(`/api/v1/${path}`, {
        method: requestOptions.method,
        headers: requestOptions.headers,
        body,
    })
        .then(requestOptions.parser)
        .catch(requestOptions.errorHandler);
}

export default class API {

    static request(path, method = 'GET', data = {}, customErrorHandling = false) {
        let requestBody = undefined;

        if (method !== 'GET') {
            requestBody = JSON.stringify(data);
        }
        let request = fetch('/api/v1' + path, {
            method: method,
            headers: {
                "Content-Type": "application/json"
            },
            body: requestBody,
        });
        if (!customErrorHandling) {
            request.catch(this.handleGenericError);
        }
        return request;
    }

    static handleDefaultResponse(response) {
        if (!response.ok) {
            return response.json().then(this.handleGenericError);
        }

        return response.json().then(data => {
            notyf.success(data.data.message);
        });
    }

    static handleGenericError(error) {
        console.error(error);
        let errorMessage = error?.message ?? error?.data?.message ?? 'An unknown error occured.';
        notyf.error(errorMessage);
        return error;
    }
}
