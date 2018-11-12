
const ACTIONS = {
    DATA_LOADED: "DATA_LOADED",
    SELECT_RECEIPT: "SELECT_RECEIPT",
    EDIT_RECEIPT: "EDIT_RECEIPT",
    ADD_ARTICLE: "ADD_ARTICLE",
    STORES_LOADED: "STORES_LOADED"
};

const URLS = {
    HOME: "http://127.0.0.1:8000/"
};

const UTILS = {

    init: function() {

        const initialState = {
            receipts: [],
            selectedReceipt: null,
            page: 1,
            pageSize: 5,
            pageCount: 1,
            stores: []
        };

        const createNewReceipt = function () {
            return { id: 0, date: "",store: {name: "", id: 0}, articles: [createNewArticle()]};
        };

        let idGenerated = 0;
        const createNewArticle = function () {
            return {id: --idGenerated, name: "", volume: 0, price: 0.00};
        };

        const rootReducer = (state = initialState, action) => {
            switch (action.type) {
                case ACTIONS.DATA_LOADED:
                    return Object.assign({}, state,
                        {
                            receipts: action.payload.receipts,
                            selectedReceipt: null,
                            page: action.payload.page,
                            pageSize: action.payload.pageSize,
                            pageCount: action.payload.pageCount
                        });
                case ACTIONS.SELECT_RECEIPT:
                    return Object.assign({}, state, {selectedReceipt: action.payload == null ?
                            createNewReceipt() : state.receipts.find(function (receipt) {return receipt.id == action.payload;})});
                case ACTIONS.EDIT_RECEIPT:
                    return Object.assign({}, state, {selectedReceipt: action.payload});
                case ACTIONS.ADD_ARTICLE:
                    let newSelected = Object.assign({}, state.selectedReceipt);
                    newSelected.articles = newSelected.articles.concat([createNewArticle()]);
                    return Object.assign({}, state, {selectedReceipt: newSelected});
                case ACTIONS.STORES_LOADED:
                    return Object.assign({}, state, {stores: action.payload});
                default:
                    return state;
            }
        };

        let store = Redux.createStore(rootReducer);

        UTILS.showPage(React.createElement(ReactRedux.Provider, {store: store}, React.createElement(ReceiptsPage)));
    },

    showPage: function(page) {
        ReactDOM.render(page, document.getElementById("reactDiv"));
    },

    ajaxCall: function(url, method, data) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open(method, url);
            xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
            xhr.onload = () => resolve(JSON.parse(xhr.responseText));
            xhr.onerror = () => reject(xhr.statusText);
            if (data) {
                xhr.send(JSON.stringify(data));
            } else {
                xhr.send();
            }

        });
    },

    validateReceipt(receipt) {
        let pattern = new RegExp("^(?:(?:31(\\/|-|\\.)(?:0?[13578]|1[02]))\\1|(?:(?:29|30)(\\/|-|\\.)(?:0?[1,3-9]|1[0-2])\\2))(?:(?:1[6-9]|[2-9]\\d)?\\d{2})$|^(?:29(\\/|-|\\.)0?2\\3(?:(?:(?:1[6-9]|[2-9]\\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\\d|2[0-8])(\\/|-|\\.)(?:(?:0?[1-9])|(?:1[0-2]))\\4(?:(?:1[6-9]|[2-9]\\d)?\\d{2})$");
        if (!pattern.test(receipt.date)) {
            return "Date is invalid";
        }

        let storeValidity = UTILS.validateStore(receipt.store);
        if (storeValidity !== true) {
            return storeValidity;
        }

        let articlesValidity = UTILS.validateReceiptArticles(receipt);
        if (articlesValidity !== true) {
            return articlesValidity;
        }

        return true;
    },

    validateStore(store) {
        return (store == null || store.name == null || store.name == "") ? "Store Name is invalid" : true;
    },

    validateReceiptArticles(receipt) {
        if (receipt.articles.length == 0) {
            return "Receipt must have at least one Article";
        }
        for (let article of receipt.articles) {
            let validity = UTILS.validateArticle(article);
            if (validity !== true) {
                return validity;
            }
        }
        return true;
    },

    validateArticle(article) {
        if (article.name == null || article.name == "") {
            return "An Article Name is invalid";
        }
        if (article.volume == null || article.volume == "" || isNaN(article.volume)) {
            return "An Article Volume is invalid";
        }
        if (article.price == null || article.price == "" ||isNaN(article.price)) {
            return "An Article Price is invalid";
        }
        return true;
    }

};