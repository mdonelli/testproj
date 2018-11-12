
class ReceiptsTable extends React.Component {

    createRows() {
        return [this.createHeaderRow()].concat(this.createDataRows());
    }

    createHeaderRow() {
        return React.createElement("tr", {key: "header"}, ["Date", "Store", "Total Kn", "Edit", "Delete"].map(function (column, index) {
            return React.createElement("th", {key: "header-" + index}, column);
        }));
    }

    createDataRows() {
        return this.props.receipts.map(function(receipt){
            return React.createElement("tr", {key: receipt.id,  className: "dataRow"}, [
                receipt.date,
                receipt.store.name,
                receipt.total,
                React.createElement(CustomButton, {handler: this.props.selectReceipt, value: receipt.id},"Edit"),
                React.createElement(CustomButton, {handler: this.props.deleteReceipt, value: receipt.id}, "Delete"),
            ].map(function(data, index) {
                return React.createElement("td", {key: receipt.id + "-" + index}, data);
            }));
        }, this);
    }

    render() {
        return React.createElement("table", {className: "cfReceipts cfTable"}, React.createElement("tbody", null, this.createRows()));
    }

}