import React from "react";
import { AiFillSave } from "react-icons/ai";

const Article = ({ article }) => {
    return (
        <div className="bg-white shadow border border-stone-400 flex flex-col">
            <div className="p-4 flex-1">
                <div className="flex justify-between">
                    <AiFillSave size={20} />
                    <img className="w-6" src={article.img} />
                </div>
                <h2 className="text-2xl mt-4">{article.title}</h2>
                <p className="text-sm">{article.description}</p>
                <div className="mt-5">Read More</div>
            </div>
            <div className="bg-stone-400 h-2 w-full" />
        </div>
    );
};

export default Article;
