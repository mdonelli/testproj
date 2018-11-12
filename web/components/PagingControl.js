
class PagingControl extends React.Component {

    constructor(props) {
        super(props);

        this.previousPage = this.previousPage.bind(this);
        this.nextPage = this.nextPage.bind(this);
        this.changePageSize = this.changePageSize.bind(this);
    }

    previousPage() {
        this.props.goToPage(this.props.page -1);
    }

    nextPage() {
        this.props.goToPage(this.props.page +1);
    }

    changePageSize(e) {
        this.props.setPageSize(e.target.value);
    }

    render() {
        return  React.createElement("div", {className: "centering"},
            React.createElement("div", {className: "inner"},
                React.createElement("div", null,
                    React.createElement("button", {disabled: this.props.page <= 1, onClick: this.previousPage}, "<"),
                    React.createElement("label", null, (this.props.page + " / " + this.props.pageCount)),
                    React.createElement("button", {disabled: this.props.page >= this.props.pageCount, onClick: this.nextPage}, ">")),
                React.createElement("div", null,
                    React.createElement("label", {htmlFor: "pageSize"}, "Results per page"),
                    React.createElement("select", {id: "pageSize", value: this.props.pageSize, onChange: this.changePageSize},
                        React.createElement("option", {value: 5}, 5),
                        React.createElement("option", {value: 10}, 10),
                        React.createElement("option", {value: 20}, 20)
            ))));
    }

}