/**
 * @class PokeSearch - Handles poke search
 */
class PokeSearch {
    constructor() {
        this.loaderMaskClass = "component-loader-mask"
        this.searchInput = "[data-poke-search-input]"
        this.dateToInput = "[data-poke-date-to-input]"
        this.dateFromInput = "[data-poke-date-from-input]"
        this.paginationContainer = "[data-pagination-container]"
        this.containerToLoad = "[data-poke-data-container]"
        this.request = null
        this.currentPhrase = document.querySelector(this.searchInput).value

        this.registerEventListeners()
    }

    /**
     * Register event listeners.
     */
    registerEventListeners() {
        document.querySelector(this.dateToInput).addEventListener('change', (event) => {
            this.performSearch()
        })

        document.querySelector(this.dateFromInput).addEventListener('change', (event) => {
            this.performSearch()
        })

        document.querySelector(this.searchInput).addEventListener('keypress', (event) => {
            if (event.key === 'Enter') {
                this.performSearch()
            }
        })

        document.querySelector(`${this.searchInput}`).addEventListener('blur', () => {
            this.performSearch();
        });
    }

    /**
     * Render the loader mask.
     */
    renderLoaderMask() {
        const loaderMask = document.createElement("div")
        loaderMask.className = this.loaderMaskClass

        const contentDiv = document.querySelector(this.containerToLoad)
        contentDiv.appendChild(loaderMask)
    }

    /**
     * Check if the loader mask is present.
     * @returns {boolean} True if the loader mask is present, false otherwise.
     */
    hasLoaderMask() {
        const loaderMask = document.querySelector(`.${this.loaderMaskClass}`)
        return !!loaderMask
    }

    /**
     * Remove the loader mask.
     */
    removeLoaderMask() {
        if (this.hasLoaderMask()) {
            document.querySelector(`.${this.loaderMaskClass}`).remove()
        }
    }

    /**
     * Perform the search.
     */
    performSearch() {
        const fromDate = document.querySelector(this.dateFromInput).value
        const toDate = document.querySelector(this.dateToInput).value
        const userName = document.querySelector(this.searchInput).value

        const xhr = new XMLHttpRequest()
        const query = `?fromDate=${fromDate}&toDate=${toDate}&userName=${userName}`
        const url = `search_poke${query}`

        if (this.currentPhrase === query) {
            return;
        }

        this.currentPhrase = url
        this.renderLoaderMask()

        /**
         * Faktiskai cia nera requestas, bet kita vertus timeout irgi tokioje vietoje prod nedeciau :D 
         * Is esmes kadangi request labai greit suvaiksto basically same ar mes timeout naikinam ar request, tik 
         * siuo atveju nereik extra variable, o in prod butu same tik ne clearTimeout(), o abort()
         */
        if (this.request) {
            clearTimeout(this.request)
            // Prevent stacking loaders
            this.removeLoaderMask()
        }

        /**
         * Delay the request to prevent unnecessary requests while typing.
         */
        this.request = setTimeout(() => {
            xhr.onreadystatechange = () => {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    const response = JSON.parse(xhr.responseText)
                
                    document.querySelector(this.containerToLoad).innerHTML = response.data
                    history.pushState({ query }, "Search Results", query);
                    this.request = null
                    this.removeLoaderMask()
                }
            };
            xhr.open('GET', url, true)
            xhr.send()
        }, 1000)
    }
}

const pokeSearch = new PokeSearch()
