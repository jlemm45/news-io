import React from 'react';
import Authenticated from '@/Layouts/Authenticated';
import { Head, useForm } from '@inertiajs/inertia-react';
import Button from '@/Components/Button';
import Input from '@/Components/Input';
import Label from '@/Components/Label';
import FileInput from '@/Components/FileInput';

export default function Profile(props) {
  const { data, setData, post, processing, progress, errors, reset } = useForm({
    name: props.auth.user.name,
    photo: props.auth.user.avatar,
    _method: 'PUT',
  });

  const submit = e => {
    e.preventDefault();

    post(route('profile.update'));
  };

  const onHandleChange = event => {
    setData(event.target.name, event.target.value);
  };

  return (
    <Authenticated>
      <Head title="Profile" />

      <div className="max-w-lg">
        <form onSubmit={submit}>
          <div className="mt-4 mb-12">
            <Label forInput="name" value="Name" />

            <Input
              type="text"
              name="name"
              value={data.name}
              className="mt-1 block w-full"
              isFocused={true}
              handleChange={onHandleChange}
            />
          </div>
          <FileInput
            className="w-full pb-8 pr-6 lg:w-1/2"
            label="Photo"
            name="photo"
            accept="image/*"
            errors={errors.photo}
            value={data.photo}
            onChange={photo => setData('photo', photo)}
          />

          {progress && (
            <progress value={progress.percentage} max="100">
              {progress.percentage}%
            </progress>
          )}

          <div className="flex items-center justify-end mt-4">
            <Button className="ml-4" processing={processing}>
              Update Profile
            </Button>
          </div>
        </form>
      </div>
    </Authenticated>
  );
}
