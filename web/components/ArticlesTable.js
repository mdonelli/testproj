
class ArticlesTable extends React.Component {

    constructor(props) {
        super(props);
        this.editArticleName = this.editArticleName.bind(this);
        this.editArticleVolume = this.editArticleVolume.bind(this);
        this.editArticlePrice = this.editArticlePrice.bind(this);
        this.deleteArticle = this.deleteArticle.bind(this);
    }

    editArticleName(e) {
        let newReceipt = Object.assign({},this.props.selectedReceipt);
        newReceipt.articles.find(function(article) {return article.id == e.target.getAttribute("articleid");})["name"]= e.target.value;
        this.props.editReceipt(newReceipt);
    }

    editArticleVolume(e) {
        let newReceipt = Object.assign({},this.props.selectedReceipt);
        newReceipt.articles.find(function(article) {return article.id == e.target.getAttribute("articleid");})["volume"]= e.target.value;
        this.props.editReceipt(newReceipt);
    }

    editArticlePrice(e) {
        let newReceipt = Object.assign({},this.props.selectedReceipt);
        newReceipt.articles.find(function(article) {return article.id == e.target.getAttribute("articleid");})["price"]= e.target.value;
        this.props.editReceipt(newReceipt);
    }

    deleteArticle(id) {
        let newReceipt = Object.assign({},this.props.selectedReceipt);
        newReceipt.articles.splice(newReceipt.articles.findIndex(function(article) {return article.id == id; }), 1);
        this.props.editReceipt(newReceipt);
    }

    createRows() {
        return [this.createHeaderRow()].concat(this.createDataRows());
    }

    createHeaderRow() {
        return React.createElement("tr", {key: "header"}, ["Name", "Volume", "Price", "Delete Article"].map(function (column, index) {
            return React.createElement("th", {key: "header-" + index}, column);
        }));
    }

    createDataRows() {
        return this.props.selectedReceipt.articles.map(function(article){
            return React.createElement("tr", {key: article.id},
                React.createElement("td", null, this.createInput("text", article.id, article.name, this.editArticleName)),
                React.createElement("td", null, this.createInput("number", article.id, article.volume, this.editArticleVolume)),
                React.createElement("td", null, this.createInput("number", article.id, article.price, this.editArticlePrice)),
                React.createElement("td", null,
                    React.createElement(CustomButton, {value: article.id, handler: this.deleteArticle, disabled: this.props.selectedReceipt.articles.length <= 1}, "Delete"))
            );
        }, this);
    }

    createInput(type, id, value, onChange) {
        return React.createElement("input", {type: type, articleid: id, value: value, onChange: onChange});
    }

    render() {
        return React.createElement("table", {className: "cfArticles cfTable"}, React.createElement("tbody", null, this.createRows()));
    }

}