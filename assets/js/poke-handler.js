/**
 * @class PokeHandler - Handles poking in the user index page
 */
class PokeHandler {
    constructor() {
        this.loaderMaskClass = "component-loader-mask"
        this.containerToLoad = "[data-user-data-container]"
        this.isRequested = false

        this.pokeButtons = document.getElementsByClassName('poke-button')
        this.init()
    }
    
    init() {
        this.listenToPokeButtons()
        document.addEventListener('userTableUpdated', (event) => {
            this.listenToPokeButtons()
        });
    }

    /**
     * Sends an AJAX request to create a new poke, when finished calls a function to update DOM
     *
     * @param {int} pokedUserId - id of a user that was poked
     */
    addPoke(pokedUserId) {
        this.renderLoaderMask()
        setTimeout(() => {
            const xhr = new XMLHttpRequest()
            xhr.open('POST', 'add_poke', true)
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')

            xhr.onreadystatechange = () => {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        this.updatePokeCount(pokedUserId)
                    } else {
                        console.error('Failed to add poke.')
                    }
                    this.removeLoaderMask();
                }
                this.isRequested = false;
            };

            const data = 'poked_user_id=' + pokedUserId
            xhr.send(data);
        }, 1000); 
    }

    /**
     * Updates poke count in DOM
     */
    updatePokeCount(pokedUserId) 
    {
        const pokeCountElement = document.getElementById('poke-count-' + pokedUserId)

        if (pokeCountElement) {
            const pokeCount = parseInt(pokeCountElement.innerHTML) + 1
            pokeCountElement.innerHTML = pokeCount
        }
    }

    /**
     * Renders loading mask on specified element
     */
    renderLoaderMask() 
    {
        const loaderMask = document.createElement('div')
        loaderMask.className = this.loaderMaskClass

        const contentDiv = document.querySelector(this.containerToLoad)
        contentDiv.appendChild(loaderMask)
    }

    /**
     * Removes loading mask
     */
    removeLoaderMask() 
    {
        const loaderMask = document.querySelector(`.${this.loaderMaskClass}`)
        if (loaderMask) {
            loaderMask.remove()
        }
    }

    /**
     * Adds click listener to poke buttons in current DOM
     */
    listenToPokeButtons() 
    {
        for (let i = 0; i < this.pokeButtons.length; i++) {
            this.pokeButtons[i].addEventListener('click', (event) => {
                const pokedUserId = event.target.getAttribute('data-user-id')

                if (!this.isRequested) {
                    this.isRequested = true
                    this.addPoke(pokedUserId)
                }
            });
        }
    }
}

const pokeHandler = new PokeHandler()
