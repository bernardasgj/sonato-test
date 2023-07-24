/**
 * @class UserSearch - Handles user search
 */
class UserSearch {
    constructor() {
        this.loaderMaskClass = "component-loader-mask"
        this.searchInput = "[data-user-search-input]"
        this.paginationContainer = "[data-pagination-container]"
        this.userTable = "[data-user-table]"
        this.containerToLoad = "[data-user-data-container]"
        this.request = null
        this.currentPhrase = document.querySelector(`${this.searchInput}`).value;

        document.querySelector(`${this.searchInput}`).addEventListener('keypress', (event) => {
            if (event.key === 'Enter') {
                this.searchUsers();
            }
        });

        document.querySelector(`${this.searchInput}`).addEventListener('blur', () => {
            this.searchUsers();
        });
    }


    renderLoaderMask() 
    {
        var loaderMask = document.createElement("div");
        loaderMask.className = this.loaderMaskClass;

        var contentDiv = document.querySelector(`${this.containerToLoad}`);
        contentDiv.appendChild(loaderMask);
    }

    hasLoaderMask() 
    {
        var loaderMask = document.querySelector(`${this.containerToLoad}`);
        return !!loaderMask;
    }

    removeLoaderMask() 
    {
        if (this.hasLoaderMask()) {
            document.querySelector(`.${this.loaderMaskClass}`).remove();
        }
    }

    searchUsers() 
    {
        var searchTerm = document.querySelector(`${this.searchInput}`).value;
        
        if (this.currentPhrase === searchTerm) {
            return
        }

        this.currentPhrase = searchTerm

        if (this.request) {
            clearTimeout(this.request);
            // Prevent stacking loaders
            this.removeLoaderMask()
        }

        this.renderLoaderMask()

        this.request = setTimeout(() => {
            var xmlhttp = new XMLHttpRequest()
    
            xmlhttp.onreadystatechange = () => {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    var response = JSON.parse(xmlhttp.responseText)
                    document.querySelector(this.containerToLoad).innerHTML = response.data

                    const userTableUpdateEvent = new CustomEvent('userTableUpdated', {
                        detail: {
                            tableContent: response.tableContent,
                        },
                    });
                    document.dispatchEvent(userTableUpdateEvent)
                    this.request = null

                    history.pushState({ searchTerm }, "Search Results", "?q=" + searchTerm)
                }
            }
    
            xmlhttp.open("GET", "user_search?q=" + searchTerm, true)
            xmlhttp.send()
        }, 1000)
    }
}

const userSearch = new UserSearch()
