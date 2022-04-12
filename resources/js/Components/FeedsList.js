import React, { useState } from "react";
import Feed from "@/Components/Feed";
import Modal from "@/Components/Modal";
import { AiOutlinePlusSquare } from "react-icons/ai";
import AddFeed from "./Forms/AddFeed";

const FeedsList = ({ feeds }) => {
    const [showModal, setModal] = useState(false);
    return (
        <>
            <div className="w-72 mr-10 sticky top-28 h-fit">
                {feeds.map((feed) => (
                    <Feed key={feed.id} feed={feed} />
                ))}
                <button
                    onClick={() => {
                        setModal(true);
                    }}
                    className="modal-label border-dashed border-2 border-stone-300 px-4 py-2 flex items-center w-full justify-items-center hover:bg-gray-200"
                >
                    <div className="flex">
                        <AiOutlinePlusSquare size={20} />{" "}
                        <span className="ml-1">Add a Feed</span>
                    </div>
                </button>
            </div>
            <Modal
                modalID="feed-modal"
                visible={showModal}
                onClose={() => {
                    setModal(false);
                }}
            >
                <h3 className="font-bold text-lg mb-2">
                    Add a valid RSS feed url to add to your feed.
                </h3>
                <AddFeed />
            </Modal>
        </>
    );
};

export default FeedsList;
