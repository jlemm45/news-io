import React from 'react';
import { useForm } from '@inertiajs/inertia-react';
import Input from '../Input';
import Button from '../Button';
import ValidationErrors from '../ValidationErrors';

const AddFeed = () => {
  const { post, setData, processing, errors } = useForm({ url: '' });

  const submit = e => {
    e.preventDefault();

    post(route('feed.new'));
  };

  return (
    <form onSubmit={submit}>
      <ValidationErrors errors={errors} />
      <Input
        name="url"
        isFocused
        type="text"
        placeholder="Enter a valid URL"
        className="input w-full"
        handleChange={e => setData('url', e.target.value)}
      />

      <Button processing={processing} type="submit" className="btn mt-4">
        Add Feed
      </Button>
    </form>
  );
};

export default AddFeed;
