import React from "react";
import Authenticated from "@/Layouts/Authenticated";
import { Head } from "@inertiajs/inertia-react";
import Article from "@/Components/Article";
import Feed from "@/Components/Feed";

export default function Feeds(props) {
    return (
        <Authenticated auth={props.auth} errors={props.errors}>
            <Head title="Feeds" />

            <div className="max-w-7xl mx-auto mt-10">
                <div className="flex">
                    <div className="w-72 mr-10">
                        {props.feeds.map((feed) => (
                            <Feed key={feed.id} feed={feed} />
                        ))}
                    </div>
                    <div className="flex-1">
                        <div className="grid grid-cols-3 gap-4">
                            {props.articles.map((article) => (
                                <Article key={article.id} article={article} />
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        </Authenticated>
    );
}
