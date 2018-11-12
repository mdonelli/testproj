
class ConnectedReceiptsPage extends React.Component {

    constructor(props) {
        super(props);
        this.saveReceipt = this.saveReceipt.bind(this);
        this.deleteReceipt = this.deleteReceipt.bind(this);
        this.backToReceiptList = this.backToReceiptList.bind(this);
        this.loadData = this.loadData.bind(this);
        this.goToPage = this.goToPage.bind(this);
        this.setPageSize = this.setPageSize.bind(this);
    }

    componentDidMount() {
        this.loadData();
    }

    loadData() {
        this.props.getReceipts(this.props.page, this.props.pageSize);
    }

    saveReceipt() {
        let validity = UTILS.validateReceipt(this.props.selectedReceipt);
        if (validity !== true) {
            alert(validity);
        } else {
            UTILS.ajaxCall(URLS.HOME + "receipt/" + this.props.selectedReceipt.id, "POST", this.props.selectedReceipt)
                .then(() => this.loadData()).catch(msg => alert(msg));
        }
    }

    deleteReceipt(id) {
        UTILS.ajaxCall(URLS.HOME + "receipt/delete/" + id, "GET")
            .then(() => this.loadData()).catch(msg => alert(msg));
    }

    backToReceiptList() {
        this.props.editReceipt(null);
    }

    goToPage(page) {
        this.props.getReceipts(page, this.props.pageSize);
    }

    setPageSize(size) {
        this.props.getReceipts(this.props.page, size);
    }

    createChildren() {
        if (this.props.selectedReceipt) {
            return [
                React.createElement(ReceiptDetails, {key: "receiptDetails", selectedReceipt: this.props.selectedReceipt, stores: this.props.stores, editReceipt: this.props.editReceipt, getStores: this.props.getStores}),
                React.createElement(ArticlesTable, {key: "articlesList", selectedReceipt: this.props.selectedReceipt, editReceipt: this.props.editReceipt}),
                React.createElement("div", {key: "buttonsDiv", className: "centering"},
                    React.createElement("div", {className: "inner"},
                        React.createElement("button", {onClick: this.props.addArticle}, "New Article"),
                        React.createElement("button", {onClick: this.saveReceipt}, "Save Receipt"),
                        React.createElement("button", {onClick: this.backToReceiptList}, "Back")))
            ];
        }
        return [
            React.createElement(CustomButton, {key: "newReceiptButton", className: "newReceiptButton", handler: this.props.selectReceipt}, "New Receipt"),
            React.createElement(ReceiptsTable, {key: "receiptList", receipts: this.props.receipts, selectReceipt: this.props.selectReceipt, deleteReceipt: this.deleteReceipt}),
            React.createElement(PagingControl, {key: "paging", page: this.props.page, pageSize: this.props.pageSize, pageCount: this.props.pageCount, goToPage: this.goToPage, setPageSize: this.setPageSize})
        ];
    }

    render() {
        return React.createElement("div", {className: "centering"}, React.createElement("div", {className: "centered"}, this.createChildren()));
    }
}

const mapStateToProps = state => { return {
    receipts: state.receipts,
    selectedReceipt: state.selectedReceipt,
    page: state.page,
    pageSize: state.pageSize,
    pageCount: state.pageCount,
    stores: state.stores
};};

const dataLoaded = (data) => ({ type: ACTIONS.DATA_LOADED, payload: data});
const selectReceipt = (id) => ({ type: ACTIONS.SELECT_RECEIPT, payload: id });
const editReceipt = (receipt) => ({ type: ACTIONS.EDIT_RECEIPT, payload: receipt });
const addArticle = () => ({ type: ACTIONS.ADD_ARTICLE });
const storesLoaded = (data) => ({ type: ACTIONS.STORES_LOADED, payload: data });

const mapDispatchToProps = dispatch => {
    return {
        getReceipts: (page, pageSize) => UTILS.ajaxCall(URLS.HOME + "receipts?page=" + page + "&pageSize=" + pageSize, "GET").then(data => dispatch(dataLoaded(data))).catch(msg => alert(msg)),
        selectReceipt: (id) => dispatch(selectReceipt(id)),
        editReceipt: (receipt) => dispatch(editReceipt(receipt)),
        addArticle: () => dispatch(addArticle()),
        getStores: () => UTILS.ajaxCall(URLS.HOME + "stores", "GET").then(data => dispatch(storesLoaded(data))).catch((msg => alert(msg)))
    };
};

const ReceiptsPage = ReactRedux.connect(mapStateToProps, mapDispatchToProps)(ConnectedReceiptsPage);

