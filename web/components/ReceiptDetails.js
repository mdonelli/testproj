
class ReceiptDetails extends React.Component {

    constructor(props) {
        super(props);
        this.editReceiptDate = this.editReceiptDate.bind(this);
        this.editReceiptStore = this.editReceiptStore.bind(this);
        this.props.getStores();
    }

    editReceiptDate(e) {
        this.props.editReceipt(Object.assign({}, this.props.selectedReceipt, {date: e.target.value}));
    }

    editReceiptStore(e) {
        this.props.editReceipt(Object.assign({}, this.props.selectedReceipt, {store: {id: 0, name: e.target.value}}));
    }

    createChildren() {
        return React.createElement("div", {className: "centering"},
            React.createElement("div", {className: "inner"},
                React.createElement("div", null,
                    React.createElement("label", {htmlFor: "receiptDate"}, "Date"),
                    React.createElement("input", {type: "text", id: "receiptDate", placeholder: "dd-mm-yyyy", value: this.props.selectedReceipt.date, onChange: this.editReceiptDate}),
                ),
                React.createElement("div", null,
                    React.createElement("label", {htmlFor: "receiptStore"}, "Store"),
                    React.createElement("input", {list:"storeList", type: "text", id: "receiptStore", value: this.props.selectedReceipt.store.name, onChange: this.editReceiptStore}),
                    React.createElement("datalist", {id:"storeList"}, this.props.stores.map(function(store) {
                        return React.createElement("option", {key: store.id, value: store.name});
            }, this)))
        ));
    }

    render() {
        return React.createElement("div", {className: "receiptDetails"}, this.createChildren());
    }

}